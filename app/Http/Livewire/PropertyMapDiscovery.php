<?php

namespace App\Http\Livewire;

use App\Models\Property;
use App\Models\MetroStation;
use Livewire\Component;

class PropertyMapDiscovery extends Component
{
    public float $centerLat = 19.0760;  // Mumbai default
    public float $centerLng = 72.8777;
    public float $radius = 5;  // km
    public string $city = 'Mumbai';
    public string $propertyType = '';
    public ?float $minPrice = null;
    public ?float $maxPrice = null;
    public bool $showMetro = true;
    public array $selectedPropertyIds = [];

    public function updatedCity(): void
    {
        $cityCoords = [
            'Mumbai' => ['lat' => 19.0760, 'lng' => 72.8777],
            'Delhi' => ['lat' => 28.6139, 'lng' => 77.2090],
            'Bangalore' => ['lat' => 12.9716, 'lng' => 77.5946],
            'Pune' => ['lat' => 18.5204, 'lng' => 73.8567],
            'Chennai' => ['lat' => 13.0827, 'lng' => 80.2707],
            'Hyderabad' => ['lat' => 17.3850, 'lng' => 78.4867],
        ];

        if (isset($cityCoords[$this->city])) {
            $this->centerLat = $cityCoords[$this->city]['lat'];
            $this->centerLng = $cityCoords[$this->city]['lng'];
        }
    }

    public function updateCenter(float $lat, float $lng): void
    {
        $this->centerLat = $lat;
        $this->centerLng = $lng;
    }

    public function getPropertiesProperty()
    {
        $query = Property::query()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->nearby($this->centerLat, $this->centerLng, $this->radius)
            ->with(['images']);

        if ($this->city) {
            $query->where('city', $this->city);
        }
        if ($this->propertyType) {
            $query->where('property_type', $this->propertyType);
        }
        if ($this->minPrice) {
            $query->where('price', '>=', $this->minPrice);
        }
        if ($this->maxPrice) {
            $query->where('price', '<=', $this->maxPrice);
        }

        return $query->limit(100)->get();
    }

    public function getMetroStationsProperty()
    {
        if (!$this->showMetro) return collect();

        return MetroStation::operational()
            ->nearby($this->centerLat, $this->centerLng, $this->radius)
            ->get();
    }

    public function render()
    {
        return view('livewire.property-map-discovery', [
            'properties' => $this->properties,
            'metroStations' => $this->metroStations,
        ]);
    }
}
