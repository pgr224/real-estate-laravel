<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Property;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use App\Services\PropertyFeatureService;

class PropertyList extends Component
{
    use WithPagination;

    private const FILTER_FIELDS = [
        'search',
        'minPrice',
        'maxPrice',
        'minBedrooms',
        'maxBedrooms',
        'minBathrooms',
        'maxBathrooms',
        'minArea',
        'maxArea',
        'propertyType',
        'selectedAmenities',
        'yearBuilt',
        'status',
        'sortBy',
        'sortDirection',
        'postalCode',
    ];

    public $search = '';
    public $minPrice = 0;
    public $maxPrice = 1000000;
    public $minBedrooms = 0;
    public $maxBedrooms = 10;
    public $minBathrooms = 0;
    public $maxBathrooms = 10;
    public $minArea = 0;
    public $maxArea = 10000;
    public $propertyType = '';
    public $selectedAmenities = [];
    public $yearBuilt = '';
    public $status = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $postalCode = '';
    public $latitude = null;
    public $longitude = null;
    public $radius = 10; // Default radius in km

    protected $listeners = ['applyAdvancedFilters'];

    public function mount()
    {
        $this->resetPage();
    }
    
    protected $queryString = [
        'search' => ['except' => ''],
        'minPrice' => ['except' => 0],
        'maxPrice' => ['except' => 1000000],
        'minBedrooms' => ['except' => 0],
        'maxBedrooms' => ['except' => 10],
        'minBathrooms' => ['except' => 0],
        'maxBathrooms' => ['except' => 10],
        'minArea' => ['except' => 0],
        'maxArea' => ['except' => 10000],
        'propertyType' => ['except' => ''],
        'selectedAmenities' => ['except' => []],
        'yearBuilt' => ['except' => ''],
        'status' => ['except' => ''],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'postalCode' => ['except' => ''],
    ];

    public function applyAdvancedFilters(array $filters): void
    {
        $this->fill(array_intersect_key($filters, array_flip(self::FILTER_FIELDS)));
        $this->resetPage();
    }

    public function getPropertiesProperty()
    {
        $this->validate([
            'search' => 'nullable|string|max:255',
            'minPrice' => 'numeric|min:0',
            'maxPrice' => 'numeric|min:0|gte:minPrice',
            'minBedrooms' => 'integer|min:0',
            'maxBedrooms' => 'integer|min:0|gte:minBedrooms',
            'minBathrooms' => 'integer|min:0',
            'maxBathrooms' => 'integer|min:0|gte:minBathrooms',
            'minArea' => 'numeric|min:0',
            'maxArea' => 'numeric|min:0|gte:minArea',
            'selectedAmenities' => 'array',
            'selectedAmenities.*' => 'string|max:255',
            'propertyType' => 'nullable|string|max:100',
            'yearBuilt' => [
                'nullable',
                Rule::when(
                    $this->yearBuilt !== '' && $this->yearBuilt !== null,
                    ['integer', 'min:1800', 'max:' . now()->year],
                ),
            ],
            'status' => ['nullable', Rule::in([null, '', 'For Sale', 'For Rent', 'Sold', 'Rented'])],
            'sortBy' => 'in:created_at,price_asc,price_desc,bedrooms',
            'sortDirection' => 'in:asc,desc',
            'postalCode' => 'nullable|string|max:32',
        ]);

        $cacheKey = $this->getCacheKey();

        $properties = Cache::remember($cacheKey, now()->addMinutes(15), function () {
            $query = Property::query()
                ->priceRange($this->minPrice, $this->maxPrice)
                ->bedrooms($this->minBedrooms, $this->maxBedrooms)
                ->bathrooms($this->minBathrooms, $this->maxBathrooms)
                ->areaRange($this->minArea, $this->maxArea)
                ->with(['features', 'images']);

            if (filled($this->search)) {
                $query->search(trim($this->search));
            }

            if (filled($this->propertyType)) {
                $query->propertyType($this->propertyType);
            }

            if ($this->selectedAmenities !== []) {
                $query->hasAmenities($this->selectedAmenities);
            }

            if (filled($this->yearBuilt)) {
                $query->where('year_built', '>=', (int) $this->yearBuilt);
            }

            if (filled($this->status)) {
                $query->where('status', $this->status);
            }

            if (filled($this->postalCode)) {
                $query->postalCode(trim($this->postalCode));
            }

            [$sortField, $sortDirection] = match ($this->sortBy) {
                'price_asc' => ['price', 'asc'],
                'price_desc' => ['price', 'desc'],
                'bedrooms' => ['bedrooms', $this->sortDirection === 'asc' ? 'asc' : 'desc'],
                default => ['created_at', $this->sortDirection === 'asc' ? 'asc' : 'desc'],
            };

            return $query
                ->orderBy($sortField, $sortDirection)
                ->paginate(12);
        });

        $this->dispatch('propertiesUpdated', $properties->items());
        return $properties;
    }

    public function updated(string $propertyName): void
    {
        $field = explode('.', $propertyName, 2)[0];

        if (in_array($field, self::FILTER_FIELDS, true)) {
            $this->resetPage();
        }
    }

    public function applySearch(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(...self::FILTER_FIELDS);
        $this->minPrice = 0;
        $this->maxPrice = 1000000;
        $this->maxBedrooms = 10;
        $this->maxBathrooms = 10;
        $this->maxArea = 10000;
        $this->sortBy = 'created_at';
        $this->sortDirection = 'desc';
        $this->selectedAmenities = [];
        $this->resetPage();
    }

    public function applyFilters(array $filters): void
    {
        $this->fill(array_intersect_key($filters, array_flip(self::FILTER_FIELDS)));
        $this->resetPage();
    }

    private function getFilters(): array
    {
        return [
            'search' => $this->search,
            'minPrice' => $this->minPrice,
            'maxPrice' => $this->maxPrice,
            'minBedrooms' => $this->minBedrooms,
            'maxBedrooms' => $this->maxBedrooms,
            'minBathrooms' => $this->minBathrooms,
            'maxBathrooms' => $this->maxBathrooms,
            'minArea' => $this->minArea,
            'maxArea' => $this->maxArea,
            'propertyType' => $this->propertyType,
            'selectedAmenities' => $this->selectedAmenities,
            'yearBuilt' => $this->yearBuilt,
            'status' => $this->status,
            'sortBy' => $this->sortBy,
            'sortDirection' => $this->sortDirection,
            'postalCode' => $this->postalCode,
        ];
    }

    private function getCacheKey()
    {
        return 'properties_' . md5(json_encode([
            ...$this->getFilters(),
            'page' => $this->getPage(),
        ]));
    }
    
    
    protected $propertyFeatureService;
    
    public function boot(PropertyFeatureService $propertyFeatureService)
    {
        $this->propertyFeatureService = $propertyFeatureService;
    }
    
    public function render()
    {
        return view('livewire.property-list', [
            'properties' => $this->getPropertiesProperty(),
            'amenities' => $this->propertyFeatureService->getFeatures(),
        ])->layout('layouts.app');
    }
    
    public function viewProperty($propertyId)
    {
        $property = Property::findOrFail($propertyId);
        Activity::create([
            'user_id' => auth()->id(),
            'type' => 'property_view',
            'description' => "Viewed property: {$property->title}",
            'property_id' => $propertyId,
        ]);
    
        $this->emit('updateRecommendations');
    }
}
