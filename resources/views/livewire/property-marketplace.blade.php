<div class="flex flex-col w-full pb-8 select-none" x-data="{ viewMode: 'grid', showMapPreview: false, activeTab: 'buy' }">
    {{-- Top Command & Discovery Bar --}}
    <div class="px-4 pt-3 pb-2 flex flex-col gap-3">
        {{-- Search Input Group --}}
        <div class="relative flex items-center w-full">
            <div class="absolute left-3.5 flex items-center pointer-events-none text-primary">
                <span class="material-symbols-outlined text-[20px]">search</span>
            </div>
            <input wire:model.live.debounce.300ms="search"
                   class="w-full bg-surface-container text-on-surface text-xs font-normal pl-10 pr-24 py-3 rounded-xl outline-none placeholder:text-on-surface-variant focus:bg-surface-container-high transition-colors tracking-tight"
                   placeholder="2 BHK, Villas, Auctions in Mumbai, SG Highway..." type="text"/>
            <div class="absolute right-2 flex items-center gap-1">
                <button aria-label="AI Voice Search"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-on-surface-variant hover:text-tertiary transition-colors bg-surface-container-high/60 active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">mic</span>
                </button>
                <button aria-label="Open Filters" wire:click="$toggle('showFilters')"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-on-surface-variant hover:text-primary transition-colors bg-surface-container-high/60 active:scale-95 relative">
                    <span class="material-symbols-outlined text-[18px]">tune</span>
                    @if(count($activeFilters ?? []) > 0)
                        <span class="absolute top-1.5 right-1.5 w-1.5 h-1.5 rounded-full bg-primary ring-2 ring-surface-container"></span>
                    @endif
                </button>
            </div>
        </div>

        {{-- Segmented Transaction Types --}}
        <div class="w-full overflow-x-auto no-scrollbar py-0.5">
            <div class="flex items-center gap-1.5 bg-surface-container-low p-1 rounded-xl w-max">
                @foreach(['buy' => 'Buy', 'rent' => 'Rent', 'auctions' => 'Bank Auctions', 'commercial' => 'Commercial', 'plots' => 'Plots'] as $tab => $label)
                    <button wire:click="$set('transactionType', '{{ $tab }}')"
                            class="px-3.5 py-1.5 rounded-lg text-xs font-medium transition-all flex items-center gap-1.5
                            {{ ($transactionType ?? 'buy') === $tab ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:text-on-surface' }}">
                        <span>{{ $label }}</span>
                        @if($tab === 'auctions')
                            <span class="w-1.5 h-1.5 rounded-full bg-tertiary animate-pulse"></span>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Micro Filter Pill Rail --}}
        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar py-0.5 -mx-4 px-4">
            @foreach($filterChips ?? [] as $chip)
                <button wire:click="toggleFilter('{{ $chip['key'] }}')"
                        class="filter-pill flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-medium whitespace-nowrap active:scale-95
                        {{ $chip['active'] ? 'bg-primary-container/25 text-primary' : 'bg-surface-container text-on-surface-variant hover:text-on-surface' }}">
                    @if($chip['icon'] ?? false)
                        <span class="material-symbols-outlined text-[14px] {{ $chip['iconColor'] ?? 'text-tertiary' }}">{{ $chip['icon'] }}</span>
                    @endif
                    <span>{{ $chip['label'] }}</span>
                    @if($chip['count'] ?? false)
                        <span>({{ $chip['count'] }})</span>
                    @endif
                </button>
            @endforeach
        </div>

        {{-- Discovery Context & Interactive View Mode Switcher --}}
        <div class="flex items-center justify-between pt-1">
            <div class="flex items-baseline gap-1.5">
                <span class="text-xs font-semibold text-on-surface tracking-tight">{{ number_format($totalCount ?? 0) }} Verified Homes</span>
                <span class="text-[10px] text-on-surface-variant">in {{ $locationLabel ?? 'your area' }}</span>
            </div>
            {{-- Interactive Grid / Map Switcher --}}
            <div class="flex items-center p-0.5 bg-surface-container rounded-lg">
                <button @click="viewMode = 'grid'; showMapPreview = false"
                        :class="viewMode === 'grid' ? 'bg-surface-container-high text-on-surface' : 'text-on-surface-variant hover:text-on-surface'"
                        class="flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-medium transition-all">
                    <span class="material-symbols-outlined text-[15px]">grid_view</span>
                    <span>List</span>
                </button>
                <button @click="viewMode = 'map'; showMapPreview = true"
                        :class="viewMode === 'map' ? 'bg-surface-container-high text-on-surface' : 'text-on-surface-variant hover:text-on-surface'"
                        class="flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-medium transition-all">
                    <span class="material-symbols-outlined text-[15px]">map</span>
                    <span>Map</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Interactive Map Preview Panel --}}
    <div x-show="showMapPreview" x-transition class="px-4 pb-3">
        <div class="relative w-full h-56 rounded-xl overflow-hidden bg-surface-container shadow-md">
            <div class="w-full h-full bg-cover bg-center" id="map-preview-container"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-background/90 via-transparent to-background/30 pointer-events-none"></div>
            @foreach($mapPins ?? [] as $pin)
                <div class="absolute flex flex-col items-center group cursor-pointer" style="top: {{ $pin['top'] }}; left: {{ $pin['left'] }};">
                    <div class="bg-{{ $pin['color'] ?? 'primary' }} text-on-{{ $pin['color'] ?? 'primary' }} text-[10px] font-bold px-2 py-0.5 rounded-full shadow-lg flex items-center gap-1">
                        @if($pin['icon'] ?? false)
                            <span class="material-symbols-outlined text-[11px]">{{ $pin['icon'] }}</span>
                        @endif
                        <span>{{ $pin['label'] }}</span>
                    </div>
                    <div class="w-2 h-2 bg-{{ $pin['color'] ?? 'primary' }} rotate-45 -mt-1"></div>
                </div>
            @endforeach
            <div class="absolute top-2.5 left-2.5 flex items-center gap-1.5">
                <div class="px-2 py-1 rounded-md bg-surface-container-lowest/80 backdrop-blur-md text-[10px] font-medium text-tertiary flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-tertiary"></span>
                    <span>High-Appreciation Zone (12-15%)</span>
                </div>
            </div>
            <button wire:click="recenterMap"
                    class="absolute bottom-3 right-3 w-8 h-8 rounded-lg bg-surface-container-highest text-on-surface flex items-center justify-center shadow-lg active:scale-90 transition-transform">
                <span class="material-symbols-outlined text-[18px]">my_location</span>
            </button>
        </div>
    </div>

    {{-- Listings Stream --}}
    <div class="px-4 flex flex-col gap-3.5">
        @forelse($properties ?? [] as $property)
            <article class="w-full bg-surface-container rounded-xl overflow-hidden shadow-sm flex flex-col transition-all hover:bg-surface-container-high/60 group">
                {{-- Media Frame with Video Reel Preview --}}
                <div class="relative w-full h-48 bg-surface-container-lowest overflow-hidden">
                    <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                         alt="{{ $property->title }}"
                         src="{{ $property->getFirstMediaUrl('images') ?: asset('build/images/property-placeholder.png') }}"/>
                    <div class="absolute inset-0 bg-gradient-to-t from-surface-container via-transparent to-transparent opacity-90"></div>

                    {{-- Reel Preview Badge --}}
                    @if($property->media->where('type', 'reel')->count())
                        <div class="absolute top-3 left-3 flex items-center gap-1.5">
                            <div class="px-2 py-1 rounded-md bg-surface-container-lowest/85 backdrop-blur-md text-on-primary-container flex items-center gap-1 text-[11px] font-medium shadow-md">
                                <span class="material-symbols-outlined text-[14px] text-primary" style="font-variation-settings: 'FILL' 1;">videocam</span>
                                <span>{{ $property->media->where('type', 'reel')->first()->duration ?? '24s' }} Walkthrough Reel</span>
                            </div>
                        </div>
                    @endif

                    {{-- Favorite Trigger --}}
                    <button wire:click="toggleFavorite({{ $property->id }})" aria-label="Save Property"
                            class="absolute top-3 right-3 w-8 h-8 rounded-lg bg-surface-container-lowest/80 backdrop-blur-md flex items-center justify-center text-on-surface-variant hover:text-error active:scale-90 transition-transform">
                        <span class="material-symbols-outlined text-[18px]" @if(in_array($property->id, $favorites ?? [])) style="font-variation-settings: 'FILL' 1; color: var(--error);" @endif>favorite</span>
                    </button>

                    {{-- Play Button Overlay --}}
                    @if($property->media->where('type', 'reel')->count())
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <div class="w-11 h-11 rounded-full bg-surface-container-highest/80 backdrop-blur-md text-primary flex items-center justify-center shadow-xl group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-[24px] ml-0.5" style="font-variation-settings: 'FILL' 1;">play_arrow</span>
                            </div>
                        </div>
                    @endif

                    {{-- Quick Spec Tagging Strip --}}
                    <div class="absolute bottom-2.5 left-3 flex items-center gap-1.5">
                        @if($property->possession_status === 'ready')
                            <span class="px-2 py-0.5 rounded bg-surface-container-highest/90 text-on-surface text-[10px] font-medium">Ready Possession</span>
                        @endif
                        @if($property->rera_number)
                            <span class="px-2 py-0.5 rounded bg-tertiary-container text-tertiary-fixed text-[10px] font-semibold flex items-center gap-0.5">
                                <span class="material-symbols-outlined text-[11px]">verified</span>
                                RERA
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Card Intelligence & Details --}}
                <div class="p-3.5 flex flex-col gap-2.5">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex flex-col min-w-0">
                            <h2 class="text-sm font-semibold text-on-surface truncate tracking-tight">{{ $property->title }}</h2>
                            <p class="text-xs text-on-surface-variant truncate">
                                {{ $property->bedrooms }} BHK {{ $property->property_type }} • {{ number_format($property->carpet_area_sqft ?? $property->area) }} sq.ft • {{ $property->locality }}, {{ $property->city }}
                            </p>
                        </div>
                        <div class="flex flex-col items-end flex-shrink-0">
                            <span class="text-base font-bold text-on-surface leading-tight">₹{{ $property->formatted_price }}</span>
                            @if($property->price_per_sqft)
                                <span class="text-[10px] text-on-surface-variant">₹{{ number_format($property->price_per_sqft) }} / sq.ft</span>
                            @endif
                        </div>
                    </div>

                    {{-- AI Real Estate Copilot Insight Tile --}}
                    @if($property->ai_insight)
                        <div class="p-2.5 rounded-lg bg-surface-container-low flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-md bg-tertiary-container flex items-center justify-center text-tertiary flex-shrink-0">
                                    <span class="material-symbols-outlined text-[15px]">trending_up</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[10px] uppercase font-bold text-tertiary tracking-wider leading-none">AI Market Vector</span>
                                    <span class="text-[11px] text-on-surface font-medium mt-0.5">{{ $property->ai_insight }}</span>
                                </div>
                            </div>
                            <span class="text-[10px] px-2 py-0.5 rounded bg-surface-container-high text-on-surface-variant font-medium">{{ $property->volatility_label ?? 'Low Volatility' }}</span>
                        </div>
                    @endif

                    {{-- Bottom Action Row --}}
                    <div class="flex items-center justify-between pt-1">
                        <div class="flex items-center gap-2 text-on-surface-variant text-[11px]">
                            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">bed</span>{{ $property->bedrooms }} Beds</span>
                            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">bathtub</span>{{ $property->bathrooms }} Baths</span>
                            @if($property->parking_count)
                                <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">directions_car</span>{{ $property->parking_count }} Prk</span>
                            @endif
                        </div>
                        <a href="{{ route('properties.show', $property) }}" wire:navigate
                           class="px-3 py-1.5 rounded-lg bg-primary-container text-on-primary-container text-xs font-medium hover:bg-primary-container/80 active:scale-95 transition-all flex items-center gap-1">
                            <span>Inspect Reel</span>
                            <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </article>
        @empty
            {{-- Empty State --}}
            <div class="p-4 rounded-xl bg-surface-container-low flex flex-col items-center text-center gap-2 my-2">
                <div class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[20px]">auto_awesome</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-xs font-semibold text-on-surface">Can't find the right deal?</span>
                    <span class="text-[11px] text-on-surface-variant max-w-[260px] leading-relaxed mt-0.5">Let ReelEstate AI track newly filed bank auctions and unlisted off-market villas for you.</span>
                </div>
                <button wire:click="createDealAlert"
                        class="mt-1 px-3 py-1.5 rounded-lg bg-surface-container text-xs font-medium text-primary hover:bg-surface-container-high transition-colors">
                    Create AI Deal Alert
                </button>
            </div>
        @endforelse

        {{-- Pagination --}}
        @if(method_exists($properties ?? collect(), 'links'))
            <div class="mt-4">
                {{ $properties->links() }}
            </div>
        @endif
    </div>

    {{-- Dual Floating Quick Action Button Container --}}
    <aside aria-label="Quick Actions" class="fixed bottom-20 left-0 right-0 z-40 px-4 pointer-events-none flex justify-center">
        <div class="pointer-events-auto flex items-center gap-2 p-1.5 rounded-2xl bg-surface-container-lowest/90 backdrop-blur-xl shadow-2xl">
            <button wire:click="openAIMatchmaker"
                    class="flex items-center gap-2 px-3.5 py-2 rounded-xl bg-primary text-on-primary text-xs font-semibold shadow-md active:scale-95 transition-transform">
                <span class="material-symbols-outlined text-[18px]">psychology</span>
                <span>AI Matchmaker</span>
            </button>
            <button wire:click="$toggle('showFilters')"
                    class="flex items-center gap-1.5 px-3 py-2 rounded-xl bg-surface-container text-on-surface text-xs font-medium hover:bg-surface-container-high active:scale-95 transition-all">
                <span class="material-symbols-outlined text-[16px] text-tertiary">tune</span>
                <span>Filters</span>
                <span class="w-4 h-4 rounded-full bg-surface-container-highest text-on-surface text-[10px] flex items-center justify-center font-bold">{{ count($activeFilters ?? []) }}</span>
            </button>
        </div>
    </aside>
</div>
