<?php

namespace App\Http\Livewire;

use App\Models\BuilderProject;
use Livewire\Component;
use Livewire\WithPagination;

class BuilderProjectHub extends Component
{
    use WithPagination;

    public string $search = '';
    public string $city = '';
    public string $status = '';
    public ?float $minPrice = null;
    public ?float $maxPrice = null;
    public string $sortBy = 'latest';
    public ?int $selectedProjectId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'city' => ['except' => ''],
        'status' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function selectProject(int $id): void
    {
        $this->selectedProjectId = $id;
    }

    public function clearSelection(): void
    {
        $this->selectedProjectId = null;
    }

    public function getSelectedProjectProperty()
    {
        if (!$this->selectedProjectId) return null;
        return BuilderProject::with('properties.images')->find($this->selectedProjectId);
    }

    public function getProjectsProperty()
    {
        $query = BuilderProject::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('builder_name', 'like', "%{$this->search}%")
                  ->orWhere('rera_number', 'like', "%{$this->search}%");
            });
        }
        if ($this->city) {
            $query->inCity($this->city);
        }
        if ($this->status) {
            $query->where('status', $this->status);
        }
        if ($this->minPrice) {
            $query->where('price_range_min', '>=', $this->minPrice);
        }
        if ($this->maxPrice) {
            $query->where('price_range_max', '<=', $this->maxPrice);
        }

        $query = match ($this->sortBy) {
            'price_low' => $query->orderBy('price_range_min', 'asc'),
            'price_high' => $query->orderBy('price_range_max', 'desc'),
            'possession' => $query->orderBy('possession_date', 'asc'),
            default => $query->latest(),
        };

        return $query->paginate(12);
    }

    public function render()
    {
        return view('livewire.builder-project-hub', [
            'projects' => $this->projects,
            'selectedProject' => $this->selectedProject,
        ]);
    }
}
