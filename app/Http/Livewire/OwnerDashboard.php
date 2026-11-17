<?php

namespace App\Http\Livewire;

use App\Models\OwnerListing;
use App\Models\Property;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class OwnerDashboard extends Component
{
    use WithPagination;

    public string $listingType = '';
    public string $status = '';
    public bool $showCreateForm = false;

    // Create form fields
    public ?int $propertyId = null;
    public string $newListingType = 'sell';
    public ?float $askingPrice = null;
    public bool $isZeroBrokerage = false;
    public string $contactPhone = '';
    public string $contactWhatsapp = '';

    protected $rules = [
        'propertyId' => 'required|exists:properties,id',
        'newListingType' => 'required|in:sell,rent',
        'askingPrice' => 'required|numeric|min:0',
        'contactPhone' => 'required|string',
    ];

    public function toggleCreateForm(): void
    {
        $this->showCreateForm = !$this->showCreateForm;
    }

    public function createListing(): void
    {
        $this->validate();

        OwnerListing::create([
            'user_id' => Auth::id(),
            'property_id' => $this->propertyId,
            'listing_type' => $this->newListingType,
            'asking_price' => $this->askingPrice,
            'is_zero_brokerage' => $this->isZeroBrokerage,
            'contact_phone' => $this->contactPhone,
            'contact_whatsapp' => $this->contactWhatsapp,
            'verification_status' => 'pending',
        ]);

        $this->reset(['propertyId', 'askingPrice', 'contactPhone', 'contactWhatsapp', 'isZeroBrokerage']);
        $this->showCreateForm = false;
        session()->flash('message', 'Listing created successfully.');
    }

    public function deleteListing(int $id): void
    {
        OwnerListing::where('id', $id)->where('user_id', Auth::id())->delete();
        session()->flash('message', 'Listing deleted.');
    }

    public function getListingsProperty()
    {
        $query = OwnerListing::query()
            ->where('user_id', Auth::id())
            ->with('property.images');

        if ($this->listingType) {
            $query->where('listing_type', $this->listingType);
        }
        if ($this->status) {
            $query->where('verification_status', $this->status);
        }

        return $query->latest()->paginate(10);
    }

    public function getUserPropertiesProperty()
    {
        return Property::where('user_id', Auth::id())->get();
    }

    public function render()
    {
        return view('livewire.owner-dashboard', [
            'listings' => $this->listings,
            'userProperties' => $this->userProperties,
        ]);
    }
}
