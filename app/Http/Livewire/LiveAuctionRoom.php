<?php

namespace App\Http\Livewire;

use App\Models\Auction;
use App\Models\AuctionRegistration;
use App\Models\Bid;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class LiveAuctionRoom extends Component
{
    public ?int $auctionId = null;
    public ?Auction $auction = null;
    public float $bidAmount = 0;
    public bool $isRegistered = false;
    public string $tab = 'live';

    protected $rules = [
        'bidAmount' => 'required|numeric|min:0',
    ];

    public function mount(?int $auctionId = null): void
    {
        $this->auctionId = $auctionId;
        if ($auctionId) {
            $this->loadAuction();
        }
    }

    public function loadAuction(): void
    {
        $this->auction = Auction::with(['property.images', 'bids.user'])
            ->find($this->auctionId);

        if ($this->auction && Auth::check()) {
            $this->isRegistered = AuctionRegistration::where('auction_id', $this->auctionId)
                ->where('user_id', Auth::id())
                ->where('emd_status', 'paid')
                ->exists();

            $this->bidAmount = ($this->auction->current_bid ?? $this->auction->starting_price)
                + $this->auction->minimum_increment;
        }
    }

    public function placeBid(): void
    {
        if (!Auth::check() || !$this->isRegistered) {
            session()->flash('error', 'You must be registered and have paid EMD to bid.');
            return;
        }

        $this->validate();

        $minimumBid = ($this->auction->current_bid ?? $this->auction->starting_price)
            + $this->auction->minimum_increment;

        if ($this->bidAmount < $minimumBid) {
            session()->flash('error', "Minimum bid is ₹" . number_format($minimumBid));
            return;
        }

        Bid::create([
            'auction_id' => $this->auctionId,
            'user_id' => Auth::id(),
            'amount' => $this->bidAmount,
        ]);

        $this->auction->update(['current_bid' => $this->bidAmount]);
        $this->loadAuction();

        session()->flash('message', 'Bid placed successfully!');
    }

    public function registerForAuction(): void
    {
        if (!Auth::check()) return;

        AuctionRegistration::firstOrCreate(
            ['auction_id' => $this->auctionId, 'user_id' => Auth::id()],
            [
                'emd_amount' => $this->auction->starting_price * 0.1,
                'emd_status' => 'pending',
                'registration_number' => 'REG-' . strtoupper(uniqid()),
            ]
        );

        session()->flash('message', 'Registration submitted. Please complete EMD payment.');
    }

    public function getActiveAuctionsProperty()
    {
        return Auction::with('property')
            ->where('status', 'active')
            ->where('end_time', '>', now())
            ->orderBy('end_time')
            ->get();
    }

    public function render()
    {
        return view('livewire.live-auction-room', [
            'activeAuctions' => $this->activeAuctions,
        ]);
    }
}
