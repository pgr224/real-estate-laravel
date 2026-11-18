<?php

namespace App\Http\Livewire;

use App\Models\BrokerProfile;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Layout('components.layouts.reelestate')]
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

    // Alias for templates that use $broker (singular)
    public function getBrokerProperty()
    {
        return $this->selectedBroker;
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

    // Dashboard stats for the selected broker
    public function getActiveDealsCountProperty(): int
    {
        if (!$this->selectedBroker) return 0;
        return $this->selectedBroker->total_deals > 0
            ? (int) ceil($this->selectedBroker->total_deals * 0.15)
            : 0;
    }

    public function getClosedDealsCountProperty(): int
    {
        if (!$this->selectedBroker) return 0;
        return $this->selectedBroker->total_deals;
    }

    public function getEstimatedPayoutProperty(): float
    {
        if (!$this->selectedBroker) return 0;
        return $this->selectedBroker->total_deals * 25000; // avg commission estimate
    }

    public function getBuyerLeadsCountProperty(): int
    {
        if (!$this->selectedBroker) return 0;
        return (int) ceil($this->selectedBroker->total_deals * 2.5);
    }

    public function getClientLeadsProperty()
    {
        // Placeholder: would come from a leads table in production
        return collect();
    }

    public function getBrokeragePipelineProperty()
    {
        // Placeholder: would come from a deals/pipeline table in production
        return collect();
    }

    public function render()
    {
        return view('livewire.certified-broker-hub', [
            'brokers' => $this->brokers,
            'selectedBroker' => $this->selectedBroker,
            'broker' => $this->broker,
            'activeDealsCount' => $this->activeDealsCount,
            'closedDealsCount' => $this->closedDealsCount,
            'estimatedPayout' => $this->estimatedPayout,
            'buyerLeadsCount' => $this->buyerLeadsCount,
            'clientLeads' => $this->clientLeads,
            'brokeragePipeline' => $this->brokeragePipeline,
        ]);
    }
}
