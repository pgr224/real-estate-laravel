<?php

namespace App\Http\Livewire;

use App\Models\PropertyMedia;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Layout('components.layouts.reelestate')]
class SmartMediaFeed extends Component
{
    use WithPagination;

    public string $mediaType = '';
    public string $city = '';
    public string $sortBy = 'latest';

    protected $queryString = [
        'mediaType' => ['except' => ''],
        'city' => ['except' => ''],
    ];

    public function updatingMediaType(): void
    {
        $this->resetPage();
    }

    public function filterByType(string $type): void
    {
        $this->mediaType = $type;
        $this->resetPage();
    }

    public function getMediaProperty()
    {
        $query = PropertyMedia::query()->with('property');

        if ($this->mediaType) {
            $query->ofType($this->mediaType);
        }
        if ($this->city) {
            $query->whereHas('property', fn($q) => $q->where('city', $this->city));
        }

        $query = match ($this->sortBy) {
            'popular' => $query->orderByDesc('views_count'),
            'most_liked' => $query->orderByDesc('likes_count'),
            default => $query->latest(),
        };

        return $query->paginate(12);
    }

    public function render()
    {
        return view('livewire.smart-media-feed', [
            'media' => $this->media,
        ]);
    }
}
