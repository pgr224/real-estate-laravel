<?php

namespace App\Http\Livewire;

use App\Models\Property;
use App\Models\PropertyMedia;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Layout('components.layouts.reelestate')]
class PropertyReelFeed extends Component
{
    use WithPagination;

    public int $currentIndex = 0;
    public string $city = '';
    public string $propertyType = '';
    public string $budgetRange = '';
    public string $possessionStatus = '';
    public string $furnishing = '';
    public array $reelIds = [];

    public function mount(): void
    {
        $this->loadReels();
    }

    public function loadReels(): void
    {
        $query = PropertyMedia::query()
            ->reels()
            ->with('property')
            ->ordered();

        if ($this->city) {
            $query->whereHas('property', fn($q) => $q->where('city', $this->city));
        }
        if ($this->propertyType) {
            $query->whereHas('property', fn($q) => $q->where('property_type', $this->propertyType));
        }

        $this->reelIds = $query->pluck('id')->toArray();
    }

    public function nextReel(): void
    {
        if ($this->currentIndex < count($this->reelIds) - 1) {
            $this->currentIndex++;
        }
    }

    public function previousReel(): void
    {
        if ($this->currentIndex > 0) {
            $this->currentIndex--;
        }
    }

    public function likeReel(int $mediaId): void
    {
        PropertyMedia::find($mediaId)?->incrementLikes();
    }

    public function viewReel(int $mediaId): void
    {
        PropertyMedia::find($mediaId)?->incrementViews();
    }

    public function getCurrentReelProperty()
    {
        if (empty($this->reelIds)) return null;
        return PropertyMedia::with('property.images')
            ->find($this->reelIds[$this->currentIndex] ?? null);
    }

    public function getPropertiesProperty()
    {
        $query = Property::query()
            ->whereHas('propertyMedia', fn($q) => $q->reels())
            ->with(['images', 'propertyMedia' => fn($q) => $q->reels()->ordered()]);

        if ($this->city) {
            $query->where('city', $this->city);
        }
        if ($this->propertyType) {
            $query->where('property_type', $this->propertyType);
        }
        if ($this->furnishing) {
            $query->where('furnishing', $this->furnishing);
        }

        return $query->latest()->paginate(10);
    }

    public function render()
    {
        return view('livewire.property-reel-feed', [
            'properties' => $this->properties,
            'currentReel' => $this->getCurrentReelProperty(),
            'totalReels' => count($this->reelIds),
        ]);
    }
}
