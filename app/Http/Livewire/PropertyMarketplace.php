<?php

namespace App\Http\Livewire;

use App\Models\Property;
use App\Models\BuilderProject;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Layout('components.layouts.reelestate')]
class PropertyMarketplace extends Component
{
    use WithPagination;

    public string $search = '';
    public string $city = '';
    public string $propertyType = '';
    public string $listingType = '';
    public ?float $minPrice = null;
    public ?float $maxPrice = null;
    public ?int $minBedrooms = null;
    public string $furnishing = '';
    public string $sortBy = 'latest';
    public bool $zeroBrokerageOnly = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'city' => ['except' => ''],
        'propertyType' => ['except' => ''],
        'sortBy' => ['except' => 'latest'],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCity(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset([
            'search', 'city', 'propertyType', 'listingType',
            'minPrice', 'maxPrice', 'minBedrooms', 'furnishing',
            'sortBy', 'zeroBrokerageOnly',
        ]);
        $this->resetPage();
    }

    public function getPropertiesProperty()
    {
        $query = Property::query()
            ->with(['images', 'features', 'builderProject']);

        if ($this->search) {
            $query->search($this->search);
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
        if ($this->minBedrooms) {
            $query->where('bedrooms', '>=', $this->minBedrooms);
        }
        if ($this->furnishing) {
            $query->where('furnishing', $this->furnishing);
        }

        $query = match ($this->sortBy) {
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'newest' => $query->orderBy('created_at', 'desc'),
            default => $query->latest(),
        };

        return $query->paginate(20);
    }

    public function render()
    {
        return view('livewire.property-marketplace', [
            'properties' => $this->properties,
        ]);
    }
}
