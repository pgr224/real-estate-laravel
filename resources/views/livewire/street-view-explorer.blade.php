<div
    x-data="{
        viewMode: 'streetview',
        heading: {{ $heading ?? 342 }},
        streetViewAvailable: @js($streetViewAvailable ?? true),
        showPropertyOverlay: true,
    }"
    class="flex flex-col w-full relative select-none"
>
    {{-- Top Floating Controls & Search Glass Overlay --}}
    <div class="sticky top-0 z-30 w-full px-3.5 pt-2 pb-2 space-y-2 pointer-events-auto bg-gradient-to-b from-background via-background/90 to-transparent">
        {{-- Search Bar with Status Badge --}}
        <div class="flex flex-col gap-1.5">
            <div class="flex items-center gap-2 bg-surface-container/95 backdrop-blur-xl px-3 py-2 rounded-xl shadow-2xl">
                <span class="material-symbols-outlined text-primary text-[20px] flex-shrink-0 animate-pulse">streetview</span>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-1.5">
                        <span class="text-xs font-semibold text-on-surface truncate tracking-tight">{{ $property->locality ?? 'SG Highway Corridor' }}</span>
                        <span
                            x-show="streetViewAvailable"
                            class="text-[9px] px-1.5 py-0.5 rounded bg-tertiary-container text-tertiary font-label uppercase tracking-widest font-bold"
                        >Street View Active</span>
                    </div>
                    <p class="text-[10px] text-on-surface-variant truncate">{{ $property->city ?? 'Ahmedabad' }} • 360° Ground Level Panorama</p>
                </div>
                <button
                    @click="$dispatch('toggle-layers')"
                    class="flex items-center gap-1 bg-surface-container-high hover:bg-surface-container-highest px-2.5 py-1.5 rounded-lg text-primary text-xs font-label transition-colors"
                >
                    <span class="material-symbols-outlined text-[16px]">layers</span>
                    <span class="font-medium text-[11px]">Layers</span>
                </button>
            </div>

            {{-- DGCA Restriction Warning / Fallback Notice --}}
            <div
                x-show="!streetViewAvailable || {{ $isDroneRestricted ? 'true' : 'false' }}"
                class="flex items-center justify-between gap-1.5 bg-surface-container-high/95 border border-primary/20 backdrop-blur-md px-2.5 py-1 rounded-lg text-[10px]"
            >
                <div class="flex items-center gap-1.5 truncate text-on-surface">
                    <span class="material-symbols-outlined text-error text-[14px]">warning</span>
                    <span class="truncate font-medium">DGCA Drone Restricted Zone — Auto-Switched to Google Street View 360°</span>
                </div>
                <button
                    wire:click="forceSatelliteView"
                    class="text-primary hover:text-on-surface font-semibold flex-shrink-0 text-[10px] underline ml-1"
                >Force 3D Satellite</button>
            </div>
        </div>

        {{-- Mode Filter Pill Strip --}}
        <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar py-0.5">
            <button
                @click="viewMode = 'streetview'; $wire.setViewMode('streetview')"
                :class="viewMode === 'streetview' ? 'bg-primary text-on-primary shadow-lg shadow-primary-container/20' : 'bg-surface-container text-on-surface hover:bg-surface-container-high'"
                class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold flex-shrink-0"
            >
                <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">streetview</span>
                <span>Google Street View</span>
                <span
                    x-show="viewMode === 'streetview'"
                    class="text-[9px] px-1 py-0.2 rounded bg-tertiary-container text-tertiary font-label uppercase font-bold"
                >360°</span>
            </button>
            <button
                @click="viewMode = 'satellite'; $wire.setViewMode('satellite')"
                :class="viewMode === 'satellite' ? 'bg-primary text-on-primary shadow-lg' : 'bg-surface-container text-on-surface hover:bg-surface-container-high'"
                class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium flex-shrink-0"
            >
                <span class="material-symbols-outlined text-primary text-[15px]">view_in_ar</span>
                <span>3D Satellite</span>
            </button>
            <button
                :class="'{{ $isDroneRestricted ? 'true' : 'false' }}' === 'true' ? 'opacity-90 cursor-not-allowed' : 'hover:text-on-surface'"
                class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-surface-container text-on-surface-variant text-xs font-medium flex-shrink-0"
                @if($isDroneRestricted) disabled @endif
            >
                <span class="material-symbols-outlined text-error text-[15px]">no_photography</span>
                <span>Drone Reels ({{ $isDroneRestricted ? 'Restricted' : 'Available' }})</span>
            </button>
            <button
                @click="viewMode = 'metro'; $wire.setViewMode('metro')"
                :class="viewMode === 'metro' ? 'bg-primary text-on-primary' : 'bg-surface-container text-on-surface-variant hover:text-on-surface'"
                class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium flex-shrink-0"
            >
                <span class="material-symbols-outlined text-tertiary text-[16px]">tram</span>
                <span>Metro Corridor</span>
            </button>
        </div>
    </div>

    {{-- Street View / Satellite Viewport --}}
    <div class="relative w-full h-[520px] overflow-hidden bg-surface-container-lowest">
        {{-- Street View Image / Embed --}}
        <div
            x-show="streetViewAvailable"
            class="absolute inset-0 w-full h-full bg-cover bg-center"
            style="background-image: url('{{ $property->getFirstMediaUrl('streetview') ?: asset('build/images/property-placeholder.png') }}');"
        ></div>

        {{-- Fallback when Street View is unavailable --}}
        <div
            x-show="!streetViewAvailable"
            class="absolute inset-0 w-full h-full flex flex-col items-center justify-center bg-surface-container-lowest text-center px-6"
        >
            <span class="material-symbols-outlined text-[64px] text-on-surface-variant mb-4">streetview</span>
            <h3 class="text-lg font-bold text-on-surface mb-2">Street View Not Available</h3>
            <p class="text-sm text-on-surface-variant max-w-md mb-6">
                Google Street View imagery is not yet available for this location.
                Try viewing the property through 3D Satellite or Drone Reels instead.
            </p>
            <div class="flex items-center gap-3">
                <button
                    @click="viewMode = 'satellite'; $wire.setViewMode('satellite')"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-on-primary font-semibold text-sm shadow-lg transition-colors"
                >
                    <span class="material-symbols-outlined text-[18px]">view_in_ar</span>
                    <span>Try 3D Satellite</span>
                </button>
                <button
                    @click="$wire.requestCoverage()"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-surface-container-high text-on-surface font-medium text-sm transition-colors"
                >
                    <span class="material-symbols-outlined text-tertiary text-[18px]">notification_add</span>
                    <span>Request Coverage</span>
                </button>
            </div>
        </div>

        {{-- Gradient Overlay --}}
        <div class="absolute inset-0 bg-gradient-to-t from-background via-transparent to-black/40 pointer-events-none"></div>

        {{-- Location Badge (Top Left) --}}
        <div class="absolute top-3 left-3 z-20 flex items-center gap-2">
            <div class="flex items-center gap-1.5 bg-surface-container-lowest/90 backdrop-blur-md px-3 py-1.5 rounded-lg shadow-xl border border-outline-variant/50">
                <span class="material-symbols-outlined text-primary text-[18px]">streetview</span>
                <div class="flex flex-col">
                    <div class="flex items-center gap-1">
                        <span class="text-xs font-bold text-on-surface">{{ $property->locality ?? 'SG Highway' }}, {{ $property->area ?? 'Bodakdev' }}</span>
                        <span class="text-[9px] bg-tertiary-container/90 text-tertiary px-1 rounded font-mono font-semibold">360°</span>
                    </div>
                    <span class="text-[10px] text-on-surface-variant">Google Street View • {{ $streetViewDate ?? 'Dec 2024' }}</span>
                </div>
            </div>
        </div>

        {{-- Compass & Zoom Controls (Top Right) --}}
        <div class="absolute top-3 right-3 z-20 flex flex-col gap-2">
            <div class="flex items-center gap-1 bg-surface-container-lowest/90 backdrop-blur-md px-2.5 py-1 rounded-lg text-[10px] text-on-surface font-mono shadow-md">
                <span class="material-symbols-outlined text-tertiary text-[14px]">explore</span>
                <span>Heading: <span x-text="heading"></span>° NNW</span>
            </div>
            <div class="flex flex-col bg-surface-container/90 backdrop-blur-md rounded-xl shadow-lg overflow-hidden">
                <button class="w-10 h-9 flex items-center justify-center text-on-surface hover:bg-surface-container-high active:bg-surface-container-highest transition-colors">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                </button>
                <div class="w-full h-px bg-surface-container-highest"></div>
                <button class="w-10 h-9 flex items-center justify-center text-on-surface hover:bg-surface-container-high active:bg-surface-container-highest transition-colors">
                    <span class="material-symbols-outlined text-[18px]">remove</span>
                </button>
            </div>
        </div>

        {{-- Property Pin Overlay --}}
        @if($property)
            <div
                x-show="showPropertyOverlay"
                class="absolute top-36 left-1/4 z-20 cursor-pointer group"
                wire:click="showPropertyDetail"
            >
                <div class="flex items-center gap-1 bg-surface-container-highest/95 backdrop-blur-md px-2.5 py-1 rounded-lg shadow-xl border border-primary/30 text-on-surface group-hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined text-primary text-[15px]">apartment</span>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold text-on-surface">{{ $property->title }}</span>
                        <span class="text-[8px] text-tertiary font-mono">{{ $property->street_frontage ?? 'Boulevard Frontage' }}</span>
                    </div>
                </div>
                <div class="w-0.5 h-6 bg-primary mx-auto"></div>
                <div class="w-2.5 h-2.5 rounded-full bg-primary ring-2 ring-background mx-auto animate-ping"></div>
            </div>
        @endif

        {{-- Navigation Guide (Bottom Center) --}}
        <div class="absolute bottom-16 left-1/2 -translate-x-1/2 z-20 pointer-events-none flex flex-col items-center gap-1 opacity-90">
            <div class="w-10 h-10 rounded-full bg-surface-container-highest/80 backdrop-blur-md flex items-center justify-center text-primary shadow-lg border border-primary/30 animate-pulse">
                <span class="material-symbols-outlined text-[24px]">straight</span>
            </div>
            <span class="text-[9px] font-mono text-on-surface bg-surface-container-lowest/80 px-2 py-0.5 rounded">Tap road to step forward</span>
        </div>

        {{-- Pegman Control (Bottom Right) --}}
        <div class="absolute bottom-12 right-4 z-20 flex flex-col items-center gap-2">
            <div class="relative w-12 h-12 rounded-full bg-surface-container-lowest/90 backdrop-blur-md border-2 border-primary/40 flex items-center justify-center shadow-2xl">
                <span class="material-symbols-outlined text-[26px] text-tertiary">person_pin_circle</span>
                <span class="absolute -top-1 -right-1 flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-primary"></span>
                </span>
            </div>
            <div class="text-[8px] text-on-surface-variant font-label uppercase tracking-widest bg-surface-container-lowest/80 px-1.5 py-0.5 rounded">Pegman Active</div>
        </div>
    </div>

    {{-- Bottom Floating Property Card & Media Preview --}}
    @if($property)
        <div class="relative z-30 w-full px-3.5 -mt-6 pb-4">
            <div class="bg-surface-container/95 backdrop-blur-2xl rounded-2xl p-4 shadow-2xl space-y-3.5">
                {{-- Street View Preview Image --}}
                <div class="relative w-full h-40 rounded-xl overflow-hidden group">
                    <div
                        class="w-full h-full bg-cover bg-center transition-transform duration-700 group-hover:scale-105"
                        style="background-image: url('{{ $property->getFirstMediaUrl('streetview') ?: asset('build/images/property-placeholder.png') }}');"
                    ></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-surface-container-lowest via-surface-container-lowest/30 to-transparent"></div>
                    <div class="absolute top-2.5 left-2.5 flex items-center gap-1.5 flex-wrap">
                        <div class="flex items-center gap-1 bg-surface-container-lowest/90 backdrop-blur-md px-2 py-0.5 rounded-md text-[10px] font-bold text-tertiary font-label">
                            <span class="material-symbols-outlined text-[14px]">360</span>
                            <span>360° STREET WALK VIEW</span>
                        </div>
                        @if($isDroneRestricted)
                            <span class="bg-error-container text-on-error-container text-[10px] px-2 py-0.5 rounded-md font-semibold uppercase tracking-wider font-label flex items-center gap-0.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-error"></span>Drone Offline (DGCA Buffer)
                            </span>
                        @endif
                    </div>
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <div class="w-12 h-12 rounded-full bg-primary/90 text-on-primary flex items-center justify-center shadow-2xl backdrop-blur-md transform group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-[28px] translate-x-0.5" style="font-variation-settings: 'FILL' 1;">streetview</span>
                        </div>
                    </div>
                    <div class="absolute bottom-2.5 left-3 right-3 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-1 text-on-surface font-mono text-[11px]">
                            <span class="material-symbols-outlined text-primary text-[14px]">person_pin_circle</span>
                            <span>Street View • {{ $property->locality ?? 'SG Highway Front' }}</span>
                        </div>
                        <span class="text-[11px] font-mono text-tertiary bg-surface-container-lowest/90 px-1.5 py-0.5 rounded">Full 360° Pano</span>
                    </div>
                </div>

                {{-- Property Info --}}
                <div class="space-y-1">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <h2 class="text-base font-bold text-on-surface tracking-tight">{{ $property->title }}</h2>
                            <p class="text-xs text-on-surface-variant">Street Level View • {{ $property->locality }}, {{ $property->city }}</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <div class="text-lg font-extrabold text-on-surface leading-tight">₹{{ number_format($property->price / 100000, 1) }} L</div>
                            <div class="text-[10px] text-on-surface-variant font-mono">₹{{ number_format($property->price_per_sqft) }}/sq.ft</div>
                        </div>
                    </div>
                    {{-- Walk Score --}}
                    <div class="flex items-center justify-between bg-surface-container-high px-2.5 py-1.5 rounded-lg text-xs">
                        <div class="flex items-center gap-1.5 text-on-surface">
                            <span class="material-symbols-outlined text-tertiary text-[16px]">verified</span>
                            <span class="text-[11px] font-medium">Street Walk Score: <strong class="text-tertiary font-bold">{{ $property->walk_score ?? '9.6' }}/10</strong></span>
                        </div>
                        <span class="text-[10px] text-on-surface-variant">Pedestrian &amp; Boulevard Rating</span>
                    </div>
                </div>

                {{-- Locality Stats Grid --}}
                <div class="grid grid-cols-4 gap-1.5 text-center">
                    <div class="bg-surface-container-low p-2 rounded-lg">
                        <span class="material-symbols-outlined text-primary text-[16px] mb-0.5">width</span>
                        <div class="text-xs font-bold text-on-surface">{{ $property->road_width ?? '45m' }}</div>
                        <div class="text-[9px] text-on-surface-variant uppercase font-label">{{ $property->road_type ?? '6-Lane Rd' }}</div>
                    </div>
                    <div class="bg-surface-container-low p-2 rounded-lg">
                        <span class="material-symbols-outlined text-primary text-[16px] mb-0.5">directions_walk</span>
                        <div class="text-xs font-bold text-on-surface">{{ $property->footfall_rating ?? 'High' }}</div>
                        <div class="text-[9px] text-on-surface-variant uppercase font-label">Footfall</div>
                    </div>
                    <div class="bg-surface-container-low p-2 rounded-lg">
                        <span class="material-symbols-outlined text-tertiary text-[16px] mb-0.5">tram</span>
                        <div class="text-xs font-bold text-tertiary">{{ $property->metro_distance ?? '320m' }}</div>
                        <div class="text-[9px] text-on-surface-variant uppercase font-label">Metro Stn</div>
                    </div>
                    <div class="bg-surface-container-low p-2 rounded-lg">
                        <span class="material-symbols-outlined text-primary text-[16px] mb-0.5">shield</span>
                        <div class="text-xs font-bold text-on-surface">{{ $property->lighting_score ?? '9.6' }}/10</div>
                        <div class="text-[9px] text-on-surface-variant uppercase font-label">Lighting</div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="grid grid-cols-2 gap-2 pt-0.5">
                    <button
                        wire:click="explore360"
                        class="w-full flex items-center justify-center gap-2 bg-primary hover:bg-primary-container text-on-primary font-semibold py-2.5 px-3 rounded-xl transition-colors shadow-lg shadow-primary-container/25 text-xs font-label"
                    >
                        <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">360</span>
                        <span>Explore 360° Street View</span>
                    </button>
                    <button
                        wire:click="whatsappDeveloper"
                        class="w-full flex items-center justify-center gap-1.5 bg-surface-container-high hover:bg-surface-container-highest text-on-surface font-medium py-2.5 px-3 rounded-xl transition-colors text-xs font-label"
                    >
                        <span class="material-symbols-outlined text-tertiary text-[18px]">chat</span>
                        <span>WhatsApp Dev</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
