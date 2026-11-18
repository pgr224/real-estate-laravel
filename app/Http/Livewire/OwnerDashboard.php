<?php

namespace App\Http\Livewire;

use App\Models\OwnerListing;
use App\Models\Property;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.reelestate')]
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

    // Dashboard stats
    public function getMyListingsCountProperty(): int
    {
        return OwnerListing::where('user_id', Auth::id())->count();
    }

    public function getNewInquiriesCountProperty(): int
    {
        // Placeholder: would come from an inquiries/leads table
        return 0;
    }

    public function getVerificationPercentProperty(): int
    {
        $total = $this->myListingsCount;
        if ($total === 0) return 0;
        $verified = OwnerListing::where('user_id', Auth::id())
            ->where('verification_status', 'verified')
            ->count();
        return (int) round(($verified / $total) * 100);
    }

    public function getInquiriesProperty()
    {
        // Placeholder: would come from an inquiries/contact_messages table
        return collect();
    }

    public function getDocumentsProperty()
    {
        // Placeholder: could aggregate documents JSON from owner listings
        $docs = collect();
        OwnerListing::where('user_id', Auth::id())
            ->whereNotNull('documents')
            ->get()
            ->each(function ($listing) use (&$docs) {
                if (is_array($listing->documents)) {
                    $docs = $docs->merge($listing->documents);
                }
            });
        return $docs;
    }

    public function getRecommendationsProperty()
    {
        // Placeholder: AI-based resale recommendations
        return collect();
    }

    public function render()
    {
        return view('livewire.owner-dashboard', [
            'listings' => $this->listings,
            'userProperties' => $this->userProperties,
            'myListingsCount' => $this->myListingsCount,
            'newInquiriesCount' => $this->newInquiriesCount,
            'verificationPercent' => $this->verificationPercent,
            'inquiries' => $this->inquiries,
            'documents' => $this->documents,
            'recommendations' => $this->recommendations,
        ]);
    }
}
