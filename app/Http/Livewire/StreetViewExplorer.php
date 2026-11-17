<?php

namespace App\Http\Livewire;

use App\Models\Property;
use App\Models\PropertyMedia;
use Livewire\Component;

class StreetViewExplorer extends Component
{
    public ?int $propertyId = null;
    public ?Property $property = null;
    public float $latitude = 0;
    public float $longitude = 0;
    public int $heading = 0;
    public int $pitch = 0;
    public int $zoom = 1;

    public function mount(?int $propertyId = null): void
    {
        if ($propertyId) {
            $this->propertyId = $propertyId;
            $this->loadProperty();
        }
    }

    public function loadProperty(): void
    {
        $this->property = Property::with(['images', 'features'])->find($this->propertyId);

        if ($this->property) {
            $this->latitude = $this->property->latitude ?? 0;
            $this->longitude = $this->property->longitude ?? 0;
        }
    }

    public function updateView(float $lat, float $lng, int $heading = 0, int $pitch = 0): void
    {
        $this->latitude = $lat;
        $this->longitude = $lng;
        $this->heading = $heading;
        $this->pitch = $pitch;
    }

    public function setZoom(int $zoom): void
    {
        $this->zoom = max(0, min(3, $zoom));
    }

    public function getStreetViewMediaProperty()
    {
        if (!$this->propertyId) return collect();

        return PropertyMedia::where('property_id', $this->propertyId)
            ->ofType('streetview')
            ->ordered()
            ->get();
    }

    public function getNearbyPropertiesProperty()
    {
        if (!$this->latitude || !$this->longitude) return collect();

        return Property::nearby($this->latitude, $this->longitude, 1)
            ->where('id', '!=', $this->propertyId)
            ->limit(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.street-view-explorer', [
            'streetViewMedia' => $this->streetViewMedia,
            'nearbyProperties' => $this->nearbyProperties,
        ]);
    }
}
