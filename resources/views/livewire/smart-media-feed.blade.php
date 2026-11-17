<div class="flex flex-col w-full pb-8 select-none" x-data="{ activeTab: 'all', showPlayer: false, activeMediaId: null }">
    {{-- Fixed Top Bar: Search & Active Filters --}}
    <div class="px-3 pt-2 pb-1.5 flex flex-col gap-2">
        {{-- Header Brand & Search Row --}}
        <div class="flex items-center gap-2">
            {{-- Search Pill Input --}}
            <div class="flex-1 flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-surface-container border border-outline-variant text-xs text-on-surface">
                <span class="material-symbols-outlined text-[17px] text-primary flex-shrink-0">search</span>
                <input wire:model.live.debounce.300ms="search"
                       class="bg-transparent border-0 p-0 text-[11px] font-medium text-on-surface placeholder:text-on-surface-variant focus:ring-0 w-full outline-none"
                       placeholder="Search reels, videos, tours..." type="text">
                <button wire:click="$toggle('showFilters')" class="ml-auto text-on-surface-variant hover:text-on-surface flex items-center">
                    <span class="material-symbols-outlined text-[15px]">tune</span>
                </button>
            </div>
        </div>

        {{-- Active Filter Tags Strip --}}
        <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar">
            @foreach($mediaTypes ?? ['all' => 'All', 'reel' => 'Reels', 'video' => 'Videos', 'virtual_tour' => '360° Tours', 'drone' => 'Drone Shots'] as $key => $label)
                <button wire:click="$set('mediaType', '{{ $key }}')"
                        @click="activeTab = '{{ $key }}'"
                        class="px-2.5 py-0.5 rounded-full text-[11px] font-medium flex items-center gap-1 flex-shrink-0
                        {{ ($mediaType ?? 'all') === $key ? 'bg-primary text-on-primary font-semibold shadow-xs' : 'bg-surface-container-highest/90 text-on-surface border border-outline-variant' }}">
                    @if($key === 'reel')
                        <span class="material-symbols-outlined text-[12px]">play_arrow</span>
                    @elseif($key === 'virtual_tour')
                        <span class="material-symbols-outlined text-[12px]">360</span>
                    @elseif($key === 'drone')
                        <span class="material-symbols-outlined text-[12px]">flight</span>
                    @endif
                    <span>{{ $label }}</span>
                    @if($key === 'all')
                        <span class="material-symbols-outlined text-[12px]">check</span>
                    @endif
                </button>
            @endforeach
            <button class="px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-surface-container-highest/90 text-on-surface flex items-center gap-1 flex-shrink-0 border border-outline-variant">
                <span class="w-1.5 h-1.5 rounded-full bg-tertiary"></span>
                <span>Ready to Move</span>
            </button>
            <button class="px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-surface-container-highest/90 text-on-surface flex items-center gap-1 flex-shrink-0 border border-outline-variant">
                <span class="material-symbols-outlined text-[12px] text-primary">verified</span>
                <span>Verified Broker</span>
            </button>
        </div>
    </div>

    {{-- Media Grid Feed --}}
    <div class="px-3 pt-2">
        <div class="grid grid-cols-2 gap-2">
            @foreach($mediaItems ?? [] as $media)
                <div class="relative rounded-xl overflow-hidden bg-surface-container-lowest group cursor-pointer"
                     wire:click="openMedia({{ $media->id }})"
                     @click="showPlayer = true; activeMediaId = {{ $media->id }}">
                    {{-- Thumbnail --}}
                    <div class="relative w-full aspect-[9/16] overflow-hidden">
                        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                             alt="{{ $media->caption ?? 'Property media' }}"
                             src="{{ $media->thumbnail_url ?: asset('build/images/property-placeholder.png') }}"/>
                        <div class="absolute inset-0 bg-gradient-to-t from-background/80 via-transparent to-transparent"></div>

                        {{-- Play Button Overlay --}}
                        @if(in_array($media->type, ['reel', 'video', 'drone']))
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="w-10 h-10 rounded-full bg-surface-container-highest/70 backdrop-blur-sm flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-[22px] text-primary ml-0.5" style="font-variation-settings: 'FILL' 1;">play_arrow</span>
                                </div>
                            </div>
                        @endif

                        {{-- Duration Badge --}}
                        @if($media->duration)
                            <div class="absolute top-2 right-2 px-1.5 py-0.5 rounded bg-surface-container-lowest/80 backdrop-blur-sm text-[9px] font-bold text-on-surface">
                                {{ $media->formatted_duration ?? $media->duration }}
                            </div>
                        @endif

                        {{-- Media Type Badge --}}
                        <div class="absolute top-2 left-2 px-1.5 py-0.5 rounded-md bg-surface-container-lowest/80 backdrop-blur-sm text-[9px] font-medium text-on-surface flex items-center gap-0.5">
                            <span class="material-symbols-outlined text-[11px] text-primary">
                                {{ $media->type === 'reel' ? 'play_circle' : ($media->type === 'virtual_tour' ? '360' : ($media->type === 'drone' ? 'flight' : 'videocam')) }}
                            </span>
                            <span>{{ ucfirst($media->type) }}</span>
                        </div>

                        {{-- Bottom Info Overlay --}}
                        <div class="absolute bottom-0 inset-x-0 p-2 flex flex-col gap-1">
                            <h3 class="text-xs font-bold text-on-surface leading-tight truncate">{{ $media->property->title ?? 'Property' }}</h3>
                            <p class="text-[10px] text-on-surface-variant truncate">{{ $media->property->locality ?? '' }}, {{ $media->property->city ?? '' }}</p>
                            @if($media->property)
                                <span class="text-xs font-bold text-primary">₹{{ $media->property->formatted_price ?? '' }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- Engagement Stats Bar --}}
                    <div class="flex items-center justify-between px-2 py-1.5 bg-surface-container">
                        <div class="flex items-center gap-2 text-on-surface-variant text-[10px]">
                            <span class="flex items-center gap-0.5">
                                <span class="material-symbols-outlined text-[12px]">visibility</span>
                                {{ $media->views_count_formatted ?? number_format($media->views_count ?? 0) }}
                            </span>
                            <span class="flex items-center gap-0.5">
                                <span class="material-symbols-outlined text-[12px]">favorite</span>
                                {{ $media->likes_count_formatted ?? number_format($media->likes_count ?? 0) }}
                            </span>
                        </div>
                        <button wire:click.stop="toggleMediaLike({{ $media->id }})"
                                class="text-on-surface-variant hover:text-error transition-colors active:scale-90">
                            <span class="material-symbols-outlined text-[14px]">favorite</span>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Empty State --}}
        @if(($mediaItems ?? collect())->isEmpty())
            <div class="flex flex-col items-center justify-center py-12 text-center">
                <div class="w-14 h-14 rounded-full bg-surface-container-high flex items-center justify-center text-primary mb-3">
                    <span class="material-symbols-outlined text-[28px]">play_circle</span>
                </div>
                <h3 class="text-sm font-semibold text-on-surface">No media found</h3>
                <p class="text-xs text-on-surface-variant mt-1 max-w-[240px]">Try adjusting your filters or search to discover property reels and videos.</p>
            </div>
        @endif

        {{-- Load More --}}
        @if(method_exists($mediaItems ?? collect(), 'hasMorePages') && $mediaItems->hasMorePages())
            <div class="flex justify-center py-4">
                <button wire:click="loadMore"
                        class="px-4 py-2 rounded-xl bg-surface-container text-on-surface text-xs font-medium hover:bg-surface-container-high transition-colors">
                    Load More
                </button>
            </div>
        @endif
    </div>
</div>
