<?php

namespace App\Http\Livewire;

use App\Models\BrokerProfile;
use Livewire\Component;
use Livewire\WithPagination;

class CertifiedBrokerHub extends Component
{
    use WithPagination;

    public string $search = '';
    public string $specialization = '';
    public string $area = '';
    public bool $verifiedOnly = true;
    public string $sortBy = 'rating';
    public ?int $selectedBrokerId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'specialization' => ['except' => ''],
        'area' => ['except' => ''],
        'verifiedOnly' => ['except' => true],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function selectBroker(int $id): void
    {
        $this->selectedBrokerId = $id;
    }

    public function clearSelection(): void
    {
        $this->selectedBrokerId = null;
    }

    public function getSelectedBrokerProperty()
    {
        if (!$this->selectedBrokerId) return null;
        return BrokerProfile::with('user')->find($this->selectedBrokerId);
    }

    public function getBrokersProperty()
    {
        $query = BrokerProfile::query()->with('user');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('agency_name', 'like', "%{$this->search}%")
                  ->orWhere('rera_id', 'like', "%{$this->search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$this->search}%"));
            });
        }
        if ($this->verifiedOnly) {
            $query->verified();
        }
        if ($this->specialization) {
            $query->where('specialization', $this->specialization);
        }
        if ($this->area) {
            $query->inArea($this->area);
        }

        $query = match ($this->sortBy) {
            'deals' => $query->orderByDesc('total_deals'),
            'experience' => $query->orderByDesc('experience_years'),
            default => $query->orderByDesc('rating'),
        };

        return $query->paginate(12);
    }

    public function render()
    {
        return view('livewire.certified-broker-hub', [
            'brokers' => $this->brokers,
            'selectedBroker' => $this->selectedBroker,
        ]);
    }
}
