<?php

namespace App\Http\Livewire;

use App\Models\Auction;
use App\Models\AuctionRegistration;
use App\Models\Bid;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

#[Layout('components.layouts.reelestate')]
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
        $this->auctionId = $auctionId ?? Auction::first()?->id;
        if ($this->auctionId) {
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

    public function refreshAuction(): void
    {
        $this->loadAuction();
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

    // Computed properties for template
    public function getActiveAuctionsProperty()
    {
        return Auction::with('property')
            ->where('status', 'active')
            ->where('end_time', '>', now())
            ->orderBy('end_time')
            ->get();
    }

    public function getBidHistoryProperty()
    {
        if (!$this->auction) return collect();
        return $this->auction->bids()
            ->with('user')
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();
    }

    public function getCurrentHighestBidProperty(): float
    {
        if (!$this->auction) return 0;
        return $this->auction->current_bid ?? $this->auction->starting_price;
    }

    public function getEmdAmountProperty(): float
    {
        if (!$this->auction) return 0;
        return $this->auction->starting_price * 0.1;
    }

    public function getSecondsRemainingProperty(): int
    {
        if (!$this->auction || !$this->auction->end_time) return 0;
        $remaining = Carbon::now()->diffInSeconds($this->auction->end_time, false);
        return max(0, (int) $remaining);
    }

    public function getLegalRiskScoreProperty(): string
    {
        // Placeholder: would come from AI analysis service
        return 'Low';
    }

    public function getTrustBadgesProperty(): array
    {
        return [
            (object) ['label' => 'RERA Clear Title Report', 'status' => 'Verified'],
            (object) ['label' => 'SARFAESI Section 13(4) Notice', 'status' => 'Certified'],
            (object) ['label' => 'Physical Possession Assured', 'status' => 'Guaranteed'],
            (object) ['label' => 'Escrow Protected Deposit', 'status' => 'Active'],
        ];
    }

    public function render()
    {
        return view('livewire.live-auction-room', [
            'auction' => $this->auction,
            'property' => $this->auction?->property,
            'activeAuctions' => $this->activeAuctions,
            'bidHistory' => $this->bidHistory,
            'currentHighestBid' => $this->currentHighestBid,
            'emdAmount' => $this->emdAmount,
            'secondsRemaining' => $this->secondsRemaining,
            'legalRiskScore' => $this->legalRiskScore,
            'trustBadges' => $this->trustBadges,
        ]);
    }
}
