<?php

namespace App\Http\Livewire;

use App\Models\PropertyMedia;
use Livewire\Component;

class PropertyReelFeed extends Component
{
    public int $currentIndex = 0;
    public string $city = '';
    public string $propertyType = '';
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

    public function render()
    {
        return view('livewire.property-reel-feed', [
            'currentReel' => $this->getCurrentReelProperty(),
            'totalReels' => count($this->reelIds),
        ]);
    }
}
