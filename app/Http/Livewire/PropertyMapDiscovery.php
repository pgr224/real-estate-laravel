<?php

namespace App\Http\Livewire;

use App\Models\Property;
use App\Models\MetroStation;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.reelestate')]
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

    public string $searchQuery = '';
    public bool $nearMetro = false;
    public bool $sarfaesi = false;
    public bool $zeroBrokerage = false;
    public bool $bhk = false;
    public ?int $selectedPropertyId = null;
    public int $zoomLevel = 13;
    public array $favorites = [];

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

    public function toggleFilter(string $filter): void
    {
        if (property_exists($this, $filter)) {
            $this->$filter = !$this->$filter;
        }
    }

    public function setBudget(?string $val): void
    {
        $this->maxPrice = $this->maxPrice == $val ? null : (float) $val;
    }

    public function searchThisArea(): void
    {
        // Re-queries with current centerLat/centerLng
    }

    public function showCommuteRing(): void
    {
        $this->radius = $this->radius == 5 ? 10 : 5;
    }

    public function recenterMap(): void
    {
        $this->updatedCity();
    }

    public function selectProperty(int $id): void
    {
        $this->selectedPropertyId = $this->selectedPropertyId === $id ? null : $id;
    }

    public function toggleFavorite(int $id): void
    {
        if (in_array($id, $this->favorites)) {
            $this->favorites = array_values(array_diff($this->favorites, [$id]));
        } else {
            $this->favorites[] = $id;
        }
    }

    public function whatsappInquiry(int $id): void
    {
        // Placeholder for WhatsApp redirect
    }

    public function getPropertiesProperty()
    {
        $query = Property::query()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->nearby($this->centerLat, $this->centerLng, $this->radius)
            ->with(['images', 'propertyMedia']);

        if ($this->searchQuery) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->searchQuery}%")
                  ->orWhere('location', 'like', "%{$this->searchQuery}%");
            });
        }
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
        if ($this->bhk) {
            $query->whereIn('bedrooms', [2, 3]);
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
        $budgetFilters = [
            ['label' => 'Under ₹50L', 'value' => 5000000],
            ['label' => '₹50L - ₹1Cr', 'value' => 10000000],
            ['label' => '₹1Cr - ₹2Cr', 'value' => 20000000],
            ['label' => 'Above ₹2Cr', 'value' => 50000000],
        ];

        return view('livewire.property-map-discovery', [
            'properties' => $this->properties,
            'metroStations' => $this->metroStations,
            'budgetFilters' => $budgetFilters,
        ]);
    }
}
