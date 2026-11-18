@push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>
@endpush

<div
    x-data="{
        viewMode: 'map',
        heatmapVisible: true,
        activePropertyId: null,
        showMetro: true,
        searchQuery: '{{ $searchQuery ?? '' }}',
        filters: {
            nearMetro: @entangle('nearMetro'),
            maxPrice: @entangle('maxPrice'),
            bhk: @entangle('bhk'),
            zeroBrokerage: @entangle('zeroBrokerage'),
            sarfaesi: @entangle('sarfaesi'),
        }
    }"
    class="flex flex-col w-full relative select-none"
>
    {{-- Top Floating Controls & Filter System --}}
    <div class="z-20 px-3 pt-2 pb-1 space-y-2.5">
        {{-- Search Bar & View Mode Toggle --}}
        <div class="flex items-center gap-2">
            <div class="flex-1 flex items-center h-11 px-3 rounded-xl bg-surface-container shadow-md">
                <span class="material-symbols-outlined text-primary text-[20px] mr-2">search</span>
                <input
                    wire:model.live.debounce.300ms="searchQuery"
                    aria-label="Search locality or landmark"
                    class="bg-transparent text-xs text-on-surface font-body w-full focus:outline-none placeholder-on-surface-variant truncate"
                    type="text"
                    placeholder="Search locality, landmark, project..."
                />
                <button aria-label="Voice Search" class="text-on-surface-variant hover:text-primary transition-colors pl-1">
                    <span class="material-symbols-outlined text-[19px]">mic</span>
                </button>
            </div>
            {{-- View Switcher --}}
            <div class="flex items-center p-1 rounded-xl bg-surface-container h-11 shadow-md">
                <button
                    @click="viewMode = 'list'"
                    :class="viewMode === 'list' ? 'bg-primary text-on-primary font-semibold shadow-sm' : 'text-on-surface-variant hover:text-on-surface'"
                    class="flex items-center justify-center px-2.5 py-1.5 rounded-lg text-xs transition-all"
                >
                    <span class="material-symbols-outlined text-[18px]">view_agenda</span>
                </button>
                <button
                    @click="viewMode = 'map'"
                    :class="viewMode === 'map' ? 'bg-primary text-on-primary font-semibold shadow-sm' : 'text-on-surface-variant hover:text-on-surface'"
                    class="flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs transition-all"
                >
                    <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">map</span>
                    <span>Map</span>
                </button>
            </div>
        </div>

        {{-- Scrollable Filter Chips --}}
        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar py-0.5 text-xs">
            {{-- Near Metro --}}
            <button
                wire:click="toggleFilter('nearMetro')"
                @class([
                    'flex-shrink-0 flex items-center gap-1.5 px-3 py-1.5 rounded-lg shadow-sm font-medium',
                    'bg-primary-container text-on-primary-container' => $nearMetro,
                    'bg-surface-container text-on-surface-variant hover:text-on-surface' => !$nearMetro,
                ])
            >
                <span class="material-symbols-outlined text-[15px] text-tertiary">directions_subway</span>
                <span>Near Metro (&lt;500m)</span>
                @if($nearMetro)
                    <span class="w-1.5 h-1.5 rounded-full bg-tertiary"></span>
                @endif
            </button>

            {{-- Heatmap Toggle --}}
            <button
                @click="heatmapVisible = !heatmapVisible"
                :class="heatmapVisible ? 'bg-surface-container-high text-on-surface' : 'bg-surface-container text-on-surface-variant opacity-50'"
                class="flex-shrink-0 flex items-center gap-1.5 px-3 py-1.5 rounded-lg shadow-sm font-medium"
            >
                <span class="material-symbols-outlined text-[15px] text-primary">local_fire_department</span>
                <span>Heatmap: Price/sqft</span>
                <div class="flex items-center gap-0.5 ml-0.5">
                    <span class="w-2 h-2 rounded-full bg-tertiary" title="< ₹6k"></span>
                    <span class="w-2 h-2 rounded-full bg-amber-400" title="6-12k"></span>
                    <span class="w-2 h-2 rounded-full bg-primary" title="> 12k"></span>
                </div>
            </button>

            {{-- Budget Filter --}}
            @foreach($budgetFilters as $budget)
                <button
                    wire:click="setBudget('{{ $budget['value'] }}')"
                    @class([
                        'flex-shrink-0 flex items-center gap-1 px-3 py-1.5 rounded-lg transition-colors',
                        'bg-primary-container text-on-primary-container' => $maxPrice === $budget['value'],
                        'bg-surface-container text-on-surface-variant hover:text-on-surface' => $maxPrice !== $budget['value'],
                    ])
                >
                    <span>{{ $budget['label'] }}</span>
                </button>
            @endforeach

            {{-- BHK Filter --}}
            <button
                wire:click="toggleFilter('bhk')"
                @class([
                    'flex-shrink-0 flex items-center gap-1 px-3 py-1.5 rounded-lg transition-colors',
                    'bg-primary-container text-on-primary-container' => $bhk,
                    'bg-surface-container text-on-surface-variant hover:text-on-surface' => !$bhk,
                ])
            >
                <span>2 & 3 BHK</span>
            </button>

            {{-- SARFAESI Auctions --}}
            <button
                wire:click="toggleFilter('sarfaesi')"
                @class([
                    'flex-shrink-0 flex items-center gap-1 px-3 py-1.5 rounded-lg transition-colors',
                    'bg-primary-container text-on-primary-container' => $sarfaesi,
                    'bg-surface-container text-on-surface-variant hover:text-on-surface' => !$sarfaesi,
                ])
            >
                <span class="material-symbols-outlined text-[14px] text-primary">gavel</span>
                <span>SARFAESI Auctions</span>
            </button>

            {{-- Zero Brokerage --}}
            <button
                wire:click="toggleFilter('zeroBrokerage')"
                @class([
                    'flex-shrink-0 flex items-center gap-1 px-3 py-1.5 rounded-lg transition-colors',
                    'bg-primary-container text-on-primary-container' => $zeroBrokerage,
                    'bg-surface-container text-on-surface-variant hover:text-on-surface' => !$zeroBrokerage,
                ])
            >
                <span class="text-tertiary font-bold">0%</span>
                <span>Brokerage</span>
            </button>
        </div>
    </div>

    {{-- Interactive Leaflet Map Container --}}
    <div
        x-show="viewMode === 'map'"
        class="relative w-full h-[580px] overflow-hidden bg-surface-container-lowest"
        wire:ignore
    >
        <div
            id="property-map"
            class="absolute inset-0 w-full h-full z-0"
            x-init="
                const map = L.map('property-map', {
                    zoomControl: false,
                    attributionControl: false
                }).setView([{{ $centerLat ?? '23.0225' }}, {{ $centerLng ?? '72.5714' }}], {{ $zoomLevel ?? 13 }});

                L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                    maxZoom: 19
                }).addTo(map);

                {{-- Property Markers --}}
                @foreach($properties as $property)
                    L.marker([{{ $property->latitude }}, {{ $property->longitude }}], {
                        icon: L.divIcon({
                            className: 'custom-pin',
                            html: `<div class='px-2 py-1 rounded-lg bg-surface-container-high text-on-surface text-[10px] font-semibold flex items-center gap-1 shadow-md hover:bg-primary transition-all cursor-pointer'>
                                <span class='w-1.5 h-1.5 rounded-full {{ $property->price_per_sqft < 6000 ? 'bg-tertiary' : ($property->price_per_sqft < 12000 ? 'bg-amber-400' : 'bg-primary') }}'></span>
                                <span>₹{{ number_format($property->price / 100000, 1) }} L • {{ $property->bhk }} BHK</span>
                            </div>`,
                            iconSize: [120, 30],
                            iconAnchor: [60, 30]
                        })
                    }).addTo(map)
                    .on('click', () => @this.selectProperty({{ $property->id }}));
                @endforeach

                {{-- Metro Station Markers --}}
                @foreach($metroStations as $station)
                    L.circleMarker([{{ $station->latitude }}, {{ $station->longitude }}], {
                        radius: 7,
                        fillColor: '{{ $station->line_color ?? '#00ffff' }}',
                        color: '#09090b',
                        weight: 2.5,
                        fillOpacity: 0.9
                    }).addTo(map)
                    .bindTooltip('{{ $station->name }}', { className: 'metro-tooltip', direction: 'top' });
                @endforeach

                {{-- Heatmap Layer --}}
                const heatData = [
                    @foreach($properties as $property)
                        [{{ $property->latitude }}, {{ $property->longitude }}, {{ $property->price_per_sqft / 15000 }}],
                    @endforeach
                ];
                const heatLayer = L.heatLayer(heatData, {
                    radius: 40,
                    blur: 25,
                    maxZoom: 17,
                    gradient: { 0.3: '#34d399', 0.6: '#f59e0b', 1.0: '#a78bfa' }
                }).addTo(map);

                $watch('heatmapVisible', (val) => {
                    if (val) map.addLayer(heatLayer);
                    else map.removeLayer(heatLayer);
                });
            "
        ></div>

        {{-- Floating Top Center "Search This Area" Pill --}}
        <div class="absolute top-3 left-1/2 -translate-x-1/2 z-10">
            <button
                wire:click="searchThisArea"
                class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-surface-container-high/90 backdrop-blur-md text-on-surface hover:bg-surface-container-highest shadow-md text-xs font-medium transition-transform active:scale-95"
            >
                <span class="material-symbols-outlined text-[15px] text-primary">sync</span>
                <span>Search this area</span>
            </button>
        </div>

        {{-- Floating Map Controls (Right Edge) --}}
        <div class="absolute top-14 right-3 z-10 flex flex-col gap-2">
            <button
                @click="$dispatch('toggle-layers')"
                aria-label="Map Layers"
                class="w-10 h-10 rounded-xl bg-surface-container-high/95 backdrop-blur-md flex items-center justify-center text-on-surface shadow-md hover:text-primary transition-colors"
            >
                <span class="material-symbols-outlined text-[20px]">layers</span>
            </button>
            <button
                wire:click="showCommuteRing"
                aria-label="15-min Transit Ring"
                class="w-10 h-10 rounded-xl bg-surface-container-high/95 backdrop-blur-md flex items-center justify-center text-tertiary shadow-md hover:brightness-110 transition-colors"
            >
                <span class="material-symbols-outlined text-[20px]">timelapse</span>
            </button>
            <button
                wire:click="recenterMap"
                aria-label="Recenter Location"
                class="w-10 h-10 rounded-xl bg-surface-container-high/95 backdrop-blur-md flex items-center justify-center text-on-surface shadow-md hover:text-primary transition-colors"
            >
                <span class="material-symbols-outlined text-[20px]">my_location</span>
            </button>
            <div class="flex flex-col rounded-xl bg-surface-container-high/95 backdrop-blur-md overflow-hidden shadow-md">
                <button aria-label="Zoom In" class="w-10 h-9 flex items-center justify-center text-on-surface hover:bg-surface-container-highest text-xs">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                </button>
                <div class="h-[1px] bg-outline-variant w-full"></div>
                <button aria-label="Zoom Out" class="w-10 h-9 flex items-center justify-center text-on-surface hover:bg-surface-container-highest text-xs">
                    <span class="material-symbols-outlined text-[18px]">remove</span>
                </button>
            </div>
        </div>

        {{-- Heatmap Mini Legend (Bottom Left of Canvas) --}}
        <div
            x-show="heatmapVisible"
            x-transition
            class="absolute bottom-4 left-3 z-10 bg-surface-container-low/90 backdrop-blur-md p-2 rounded-lg text-[10px] space-y-1 shadow-md"
        >
            <div class="text-[9px] uppercase tracking-wider text-on-surface-variant font-semibold">Avg Rate / sq.ft</div>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded bg-tertiary"></span>
                <span class="text-on-surface font-mono">&lt; ₹6,000</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded bg-amber-400"></span>
                <span class="text-on-surface font-mono">₹6k - ₹12k</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded bg-primary"></span>
                <span class="text-on-surface font-mono">&gt; ₹12,000</span>
            </div>
        </div>
    </div>

    {{-- Bottom Floating Property Preview Carousel --}}
    <div class="z-20 -mt-20 px-3 pb-3">
        <div class="flex items-stretch gap-3 overflow-x-auto no-scrollbar snap-x snap-mandatory pt-2">
            @foreach($properties as $property)
                <div
                    wire:key="property-card-{{ $property->id }}"
                    wire:click="selectProperty({{ $property->id }})"
                    @class([
                        'snap-center flex-shrink-0 w-[326px] bg-surface-container/95 backdrop-blur-xl rounded-2xl p-3 shadow-xl flex flex-col justify-between cursor-pointer transition-opacity',
                        'ring-2 ring-primary' => $selectedPropertyId === $property->id,
                        'opacity-85 hover:opacity-100' => $selectedPropertyId !== $property->id,
                    ])
                >
                    <div class="flex gap-3">
                        {{-- Thumbnail with Video Reel Badge --}}
                        <div class="relative w-28 h-28 rounded-xl overflow-hidden flex-shrink-0 bg-surface-container-high">
                            <img
                                class="w-full h-full object-cover"
                                src="{{ $property->getFirstMediaUrl('images') ?: asset('build/images/property-placeholder.png') }}"
                                alt="{{ $property->title }}"
                            />
                            @if($property->media->where('type', 'reel')->first())
                                <div class="absolute top-1.5 left-1.5 px-1.5 py-0.5 rounded bg-surface-container-lowest/80 backdrop-blur-sm text-[9px] font-medium text-on-surface flex items-center gap-0.5">
                                    <span class="material-symbols-outlined text-[10px] text-primary" style="font-variation-settings: 'FILL' 1;">play_arrow</span>
                                    <span>{{ $property->media->where('type', 'reel')->first()->duration ?? '0:24' }}</span>
                                </div>
                            @endif
                            <div class="absolute bottom-1.5 right-1.5 px-1.5 py-0.5 rounded bg-surface-container-lowest/80 backdrop-blur-sm text-[9px] font-mono text-on-surface-variant">
                                {{ $property->media->count() }} media
                            </div>
                        </div>

                        {{-- Metadata & Intel --}}
                        <div class="flex-1 min-w-0 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between gap-1">
                                    @if($property->discount_percent)
                                        <span class="text-[10px] font-semibold text-tertiary bg-tertiary-container/40 px-1.5 py-0.5 rounded uppercase tracking-wider">
                                            {{ $property->discount_percent }}% Below Market
                                        </span>
                                    @endif
                                    <button
                                        wire:click.stop="toggleFavorite({{ $property->id }})"
                                        aria-label="Save to favorites"
                                        class="text-on-surface-variant hover:text-error transition-colors"
                                    >
                                        <span class="material-symbols-outlined text-[18px]">
                                            {{ in_array($property->id, $favorites) ? 'favorite' : 'favorite_border' }}
                                        </span>
                                    </button>
                                </div>
                                <h2 class="text-sm font-bold text-on-surface truncate mt-1">{{ $property->title }}</h2>
                                <p class="text-[11px] text-on-surface-variant truncate">{{ $property->locality }}, {{ $property->city }}</p>
                            </div>
                            <div class="space-y-1 my-1">
                                <div class="flex items-baseline gap-1.5">
                                    <span class="text-base font-extrabold text-on-surface">₹{{ number_format($property->price / 100000, 1) }} L</span>
                                    <span class="text-[10px] text-on-surface-variant font-mono">₹{{ number_format($property->price_per_sqft) }}/sq.ft</span>
                                </div>
                                @if($property->nearestMetro)
                                    <div class="flex items-center gap-1 text-[11px] text-tertiary font-medium">
                                        <span class="material-symbols-outlined text-[13px]">directions_walk</span>
                                        <span class="truncate">{{ $property->nearestMetro->distance }}m to {{ $property->nearestMetro->name }} ({{ $property->nearestMetro->walk_time }} min)</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Micro Specs & Call-to-Actions --}}
                    <div class="mt-2.5 pt-2 flex items-center justify-between gap-2">
                        <div class="text-[10px] text-on-surface-variant font-mono truncate">
                            {{ $property->bhk }} BHK • {{ number_format($property->super_buildup_area_sqft) }} sq.ft
                            @if($property->is_zero_brokerage)
                                • <span class="text-tertiary font-medium">0% Brokerage</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-1.5 flex-shrink-0">
                            <button
                                wire:click.stop="whatsappInquiry({{ $property->id }})"
                                aria-label="Direct WhatsApp Contact"
                                class="w-8 h-8 rounded-lg bg-surface-container-highest hover:bg-surface-bright flex items-center justify-center text-tertiary transition-colors"
                            >
                                <span class="material-symbols-outlined text-[17px]">chat</span>
                            </button>
                            <a
                                href="{{ route('property.show', $property) }}"
                                wire:navigate
                                class="flex items-center gap-1 px-3 py-1.5 rounded-lg bg-primary text-on-primary font-semibold text-xs shadow-sm hover:brightness-110 active:scale-95 transition-all"
                            >
                                <span class="material-symbols-outlined text-[14px]">videocam</span>
                                <span>Watch Reel</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
