<?php

namespace App\Http\Livewire;

use App\Models\Property;
use App\Models\PropertyMedia;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.reelestate')]
class StreetViewExplorer extends Component
{
    public ?int $propertyId = null;
    public ?Property $property = null;
    public float $latitude = 23.1147;
    public float $longitude = 72.5413;
    public int $heading = 180;
    public int $pitch = 0;
    public int $zoom = 1;

    public bool $streetViewAvailable = true;
    public bool $isDroneRestricted = true;
    public string $streetViewDate = 'Feb 2026';

    public function mount(?int $propertyId = null): void
    {
        $this->propertyId = $propertyId ?? Property::first()?->id;
        if ($this->propertyId) {
            $this->loadProperty();
        }
    }

    public function loadProperty(): void
    {
        $this->property = Property::with(['images', 'features'])->find($this->propertyId) ?? Property::first();

        if ($this->property) {
            $this->latitude = (float) ($this->property->latitude ?? 23.1147);
            $this->longitude = (float) ($this->property->longitude ?? 72.5413);
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

    public function forceSatelliteView(): void
    {
        $this->streetViewAvailable = false;
    }

    public function getStreetViewMediaProperty()
    {
        if (!$this->propertyId) return collect();

        return PropertyMedia::where('property_id', $this->propertyId)
            ->whereIn('type', ['streetview', 'virtual_tour', 'reel'])
            ->get();
    }

    public function getNearbyPropertiesProperty()
    {
        if (!$this->latitude || !$this->longitude) return collect();

        return Property::nearby($this->latitude, $this->longitude, 5)
            ->where('id', '!=', $this->propertyId)
            ->limit(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.street-view-explorer', [
            'streetViewMedia' => $this->streetViewMedia,
            'nearbyProperties' => $this->nearbyProperties,
            'isDroneRestricted' => $this->isDroneRestricted,
            'streetViewAvailable' => $this->streetViewAvailable,
            'streetViewDate' => $this->streetViewDate,
        ]);
    }
}
