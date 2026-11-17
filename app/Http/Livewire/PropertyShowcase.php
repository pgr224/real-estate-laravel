<?php

namespace App\Http\Livewire;

use App\Models\Property;
use App\Models\PropertyMedia;
use App\Models\BuilderProject;
use Livewire\Component;

class PropertyShowcase extends Component
{
    public ?int $propertyId = null;
    public ?Property $property = null;
    public string $activeTab = 'overview';
    public bool $showContactForm = false;
    public string $contactName = '';
    public string $contactPhone = '';
    public string $contactMessage = '';

    public function mount(int $propertyId): void
    {
        $this->propertyId = $propertyId;
        $this->loadProperty();
    }

    public function loadProperty(): void
    {
        $this->property = Property::with([
            'images', 'features', 'reviews', 'builderProject',
            'user', 'neighborhood',
        ])->find($this->propertyId);
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function toggleContactForm(): void
    {
        $this->showContactForm = !$this->showContactForm;
    }

    public function submitInquiry(): void
    {
        $this->validate([
            'contactName' => 'required|string|max:255',
            'contactPhone' => 'required|string|max:20',
            'contactMessage' => 'required|string|max:1000',
        ]);

        // Store inquiry logic (could dispatch event or create a Lead)
        session()->flash('message', 'Your inquiry has been submitted. We will contact you soon!');
        $this->reset(['contactName', 'contactPhone', 'contactMessage']);
        $this->showContactForm = false;
    }

    public function getMediaProperty()
    {
        if (!$this->propertyId) return collect();

        return PropertyMedia::where('property_id', $this->propertyId)
            ->ordered()
            ->get()
            ->groupBy('type');
    }

    public function getSimilarPropertiesProperty()
    {
        if (!$this->property) return collect();
        return $this->property->similarProperties(4);
    }

    public function render()
    {
        return view('livewire.property-showcase', [
            'media' => $this->media,
            'similarProperties' => $this->similarProperties,
        ]);
    }
}
