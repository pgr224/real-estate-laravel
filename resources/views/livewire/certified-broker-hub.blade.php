<div class="flex flex-col w-full pb-8 select-none" x-data="{ showDetail: @entangle('selectedBrokerId').live }">
    {{-- Search & Filter Header --}}
    <div class="px-4 pt-3 pb-2 flex flex-col gap-3">
        {{-- Search Bar --}}
        <div class="relative flex items-center w-full">
            <div class="absolute left-3.5 flex items-center pointer-events-none text-primary">
                <span class="material-symbols-outlined text-[20px]">search</span>
            </div>
            <input wire:model.live.debounce.300ms="search"
                   class="w-full bg-surface-container text-on-surface text-xs font-normal pl-10 pr-12 py-3 rounded-xl outline-none placeholder:text-on-surface-variant focus:bg-surface-container-high transition-colors tracking-tight"
                   placeholder="Search brokers by name, agency, RERA ID..." type="text"/>
            <div class="absolute right-2 flex items-center gap-1">
                <button class="w-8 h-8 rounded-lg flex items-center justify-center text-on-surface-variant hover:text-primary transition-colors bg-surface-container-high/60 active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">tune</span>
                </button>
            </div>
        </div>

        {{-- Filter Chips --}}
        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar py-0.5 -mx-4 px-4">
            {{-- Verified Toggle --}}
            <button wire:click="$toggle('verifiedOnly')"
                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-medium whitespace-nowrap active:scale-95
                    {{ $verifiedOnly ? 'bg-tertiary-container/40 text-tertiary border border-tertiary/40' : 'bg-surface-container text-on-surface-variant hover:text-on-surface' }}">
                <span class="material-symbols-outlined text-[14px] text-tertiary">verified</span>
                <span>RERA Verified</span>
            </button>

            {{-- Specialization Filters --}}
            @foreach(['Residential' => 'home', 'Commercial' => 'business', 'Luxury' => 'diamond', 'Rental' => 'key'] as $spec => $icon)
                <button wire:click="$set('specialization', '{{ ($specialization === $spec) ? '' : $spec }}')"
                        class="flex items-center gap-1 px-3 py-1.5 rounded-lg text-[11px] font-medium whitespace-nowrap active:scale-95
                        {{ $specialization === $spec ? 'bg-primary-container/25 text-primary' : 'bg-surface-container text-on-surface-variant hover:text-on-surface' }}">
                    <span class="material-symbols-outlined text-[14px]">{{ $icon }}</span>
                    <span>{{ $spec }}</span>
                </button>
            @endforeach

            {{-- Sort --}}
            <button wire:click="$set('sortBy', '{{ $sortBy === 'rating' ? 'deals' : ($sortBy === 'deals' ? 'experience' : 'rating') }}')"
                    class="flex items-center gap-1 px-3 py-1.5 rounded-lg bg-surface-container text-on-surface-variant text-[11px] font-medium whitespace-nowrap active:scale-95">
                <span class="material-symbols-outlined text-[14px] text-primary">sort</span>
                <span>{{ $sortBy === 'rating' ? 'Top Rated' : ($sortBy === 'deals' ? 'Most Deals' : 'Most Experienced') }}</span>
            </button>
        </div>

        {{-- Results Count --}}
        <div class="flex items-baseline gap-1.5">
            <span class="text-xs font-semibold text-on-surface tracking-tight">{{ $brokers->total() }} Certified Brokers</span>
            @if($area)
                <span class="text-[10px] text-on-surface-variant">in {{ $area }}</span>
            @endif
        </div>
    </div>

    {{-- Broker Cards Grid --}}
    <div class="px-4 flex flex-col gap-3">
        @forelse($brokers as $broker)
            <article wire:click="selectBroker({{ $broker->id }})"
                     class="w-full bg-surface-container rounded-xl overflow-hidden shadow-sm flex flex-col transition-all hover:bg-surface-container-high/60 cursor-pointer group">
                <div class="p-3.5 flex flex-col gap-2.5">
                    {{-- Broker Identity Row --}}
                    <div class="flex items-start gap-3">
                        {{-- Profile Photo --}}
                        <div class="relative flex-shrink-0">
                            <div class="w-14 h-14 rounded-full bg-surface-container-high border-2 border-outline-variant overflow-hidden flex items-center justify-center">
                                @if($broker->user && $broker->user->profile_photo_path)
                                    <img class="w-full h-full object-cover" src="{{ asset('storage/' . $broker->user->profile_photo_path) }}" alt="{{ $broker->user->name }}">
                                @else
                                    <span class="material-symbols-outlined text-primary text-[28px]">person</span>
                                @endif
                            </div>
                            @if($broker->verified)
                                <div class="absolute -bottom-0.5 -right-0.5 w-5 h-5 rounded-full bg-tertiary flex items-center justify-center ring-2 ring-surface-container">
                                    <span class="material-symbols-outlined text-on-tertiary text-[12px]">check</span>
                                </div>
                            @endif
                        </div>

                        {{-- Broker Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-1.5">
                                <h3 class="text-sm font-bold text-on-surface truncate">{{ $broker->user->name ?? 'Broker' }}</h3>
                                @if($broker->verified)
                                    <span class="inline-flex items-center justify-center w-4 h-4 rounded-full bg-tertiary-container/50 text-tertiary">
                                        <span class="material-symbols-outlined text-[11px]">verified</span>
                                    </span>
                                @endif
                            </div>
                            <p class="text-[10px] text-on-surface-variant truncate mt-0.5">{{ $broker->agency_name ?? '' }}</p>
                            @if($broker->rera_id)
                                <p class="text-[10px] text-tertiary font-medium mt-0.5">RERA: {{ $broker->rera_id }}</p>
                            @endif
                        </div>

                        {{-- Rating Badge --}}
                        <div class="flex flex-col items-end flex-shrink-0">
                            <div class="flex items-center gap-0.5 px-2 py-0.5 rounded-lg bg-primary-container/30 text-primary text-xs font-bold">
                                <span class="material-symbols-outlined text-[14px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                {{ number_format($broker->rating ?? 0, 1) }}
                            </div>
                        </div>
                    </div>

                    {{-- Specialization & Stats Row --}}
                    <div class="flex items-center gap-2 flex-wrap">
                        @if($broker->specialization)
                            <span class="px-2 py-0.5 rounded bg-surface-container-highest/90 text-on-surface text-[10px] font-medium">{{ $broker->specialization }}</span>
                        @endif
                        <span class="px-2 py-0.5 rounded bg-surface-container-highest/90 text-on-surface text-[10px] font-medium flex items-center gap-0.5">
                            <span class="material-symbols-outlined text-[11px] text-tertiary">handshake</span>
                            {{ $broker->total_deals ?? 0 }} Deals
                        </span>
                        <span class="px-2 py-0.5 rounded bg-surface-container-highest/90 text-on-surface text-[10px] font-medium flex items-center gap-0.5">
                            <span class="material-symbols-outlined text-[11px] text-primary">schedule</span>
                            {{ $broker->experience_years ?? 0 }}y Exp
                        </span>
                    </div>

                    {{-- Areas Served --}}
                    @if($broker->areas_served && count($broker->areas_served) > 0)
                        <div class="flex items-center gap-1 overflow-x-auto no-scrollbar">
                            <span class="material-symbols-outlined text-[13px] text-on-surface-variant flex-shrink-0">location_on</span>
                            @foreach(array_slice($broker->areas_served, 0, 3) as $areaServed)
                                <span class="text-[10px] text-on-surface-variant">{{ $areaServed }}{{ !$loop->last ? ',' : '' }}</span>
                            @endforeach
                            @if(count($broker->areas_served) > 3)
                                <span class="text-[10px] text-primary font-medium">+{{ count($broker->areas_served) - 3 }} more</span>
                            @endif
                        </div>
                    @endif

                    {{-- Contact Buttons --}}
                    <div class="flex items-center gap-2 pt-1">
                        @if($broker->whatsapp ?? $broker->phone)
                            <a href="https://wa.me/{{ $broker->whatsapp ?? $broker->phone }}" target="_blank"
                               class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-xl bg-tertiary-container hover:bg-tertiary-container/90 text-tertiary-fixed text-xs font-semibold active:scale-95 transition-transform"
                               wire:click.stop>
                                <span class="material-symbols-outlined text-[16px]">chat</span>
                                <span>WhatsApp</span>
                            </a>
                        @endif
                        @if($broker->phone)
                            <a href="tel:{{ $broker->phone }}"
                               class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-xl bg-primary hover:bg-primary-fixed-dim text-on-primary text-xs font-semibold active:scale-95 transition-transform shadow-sm"
                               wire:click.stop>
                                <span class="material-symbols-outlined text-[16px]">call</span>
                                <span>Call Now</span>
                            </a>
                        @endif
                    </div>
                </div>
            </article>
        @empty
            <div class="p-4 rounded-xl bg-surface-container-low flex flex-col items-center text-center gap-2 my-2">
                <div class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[20px]">person_search</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-xs font-semibold text-on-surface">No brokers found</span>
                    <span class="text-[11px] text-on-surface-variant max-w-[260px] leading-relaxed mt-0.5">Try adjusting your search or filters to find certified brokers in your area.</span>
                </div>
            </div>
        @endforelse

        {{-- Pagination --}}
        @if(method_exists($brokers, 'links'))
            <div class="mt-4">
                {{ $brokers->links() }}
            </div>
        @endif
    </div>

    {{-- Selected Broker Detail Panel --}}
    @if($selectedBroker)
        <div class="fixed inset-0 z-50 bg-background/80 backdrop-blur-sm" x-show="showDetail" x-transition.opacity @click="showDetail = null; $wire.clearSelection()">
            <div class="absolute bottom-0 left-0 right-0 max-h-[85vh] bg-surface-container-lowest border-t border-outline-variant/40 rounded-t-2xl shadow-2xl flex flex-col overflow-hidden pb-safe" @click.stop>
                {{-- Header --}}
                <div class="px-4 pt-3 pb-2.5 flex items-center justify-between border-b border-outline-variant/30">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-surface-container-high border-2 border-primary/30 overflow-hidden flex items-center justify-center">
                            @if($selectedBroker->user && $selectedBroker->user->profile_photo_path)
                                <img class="w-full h-full object-cover" src="{{ asset('storage/' . $selectedBroker->user->profile_photo_path) }}" alt="{{ $selectedBroker->user->name }}">
                            @else
                                <span class="material-symbols-outlined text-primary text-[24px]">person</span>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-on-surface">{{ $selectedBroker->user->name ?? 'Broker' }}</h3>
                            <p class="text-[10px] text-on-surface-variant">{{ $selectedBroker->agency_name ?? '' }} • {{ $selectedBroker->specialization ?? '' }}</p>
                        </div>
                    </div>
                    <button wire:click="clearSelection" @click="showDetail = null"
                            class="w-8 h-8 rounded-full bg-surface-container hover:bg-surface-container-high flex items-center justify-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>

                {{-- Detail Body --}}
                <div class="flex-1 overflow-y-auto px-4 py-3 flex flex-col gap-3 no-scrollbar max-h-[calc(85vh-8rem)]">
                    {{-- Stats Grid --}}
                    <div class="grid grid-cols-3 gap-2">
                        <div class="p-2.5 rounded-lg bg-surface-container-low flex flex-col items-center gap-1">
                            <span class="text-lg font-bold text-primary">{{ number_format($selectedBroker->rating ?? 0, 1) }}</span>
                            <span class="text-[10px] text-on-surface-variant">Rating</span>
                        </div>
                        <div class="p-2.5 rounded-lg bg-surface-container-low flex flex-col items-center gap-1">
                            <span class="text-lg font-bold text-tertiary">{{ $selectedBroker->total_deals ?? 0 }}</span>
                            <span class="text-[10px] text-on-surface-variant">Deals Closed</span>
                        </div>
                        <div class="p-2.5 rounded-lg bg-surface-container-low flex flex-col items-center gap-1">
                            <span class="text-lg font-bold text-on-surface">{{ $selectedBroker->experience_years ?? 0 }}y</span>
                            <span class="text-[10px] text-on-surface-variant">Experience</span>
                        </div>
                    </div>

                    {{-- Bio --}}
                    @if($selectedBroker->bio)
                        <div class="p-3 rounded-lg bg-surface-container-low">
                            <p class="text-xs text-on-surface-variant leading-relaxed">{{ $selectedBroker->bio }}</p>
                        </div>
                    @endif

                    {{-- RERA Info --}}
                    @if($selectedBroker->rera_id)
                        <div class="p-2.5 rounded-lg bg-tertiary-container/20 border border-tertiary/20 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-tertiary">verified_user</span>
                            <div class="flex flex-col">
                                <span class="text-[10px] text-tertiary font-bold uppercase tracking-wider">RERA Registered</span>
                                <span class="text-xs text-on-surface font-medium">{{ $selectedBroker->rera_id }}</span>
                            </div>
                        </div>
                    @endif

                    {{-- Areas Served --}}
                    @if($selectedBroker->areas_served && count($selectedBroker->areas_served) > 0)
                        <div class="flex flex-col gap-1.5">
                            <span class="text-xs font-semibold text-on-surface">Areas Served</span>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($selectedBroker->areas_served as $areaServed)
                                    <span class="px-2 py-0.5 rounded bg-surface-container text-on-surface-variant text-[10px]">{{ $areaServed }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Action Footer --}}
                <div class="p-4 border-t border-outline-variant/30 bg-surface-container-low flex items-center gap-2">
                    @if($selectedBroker->whatsapp ?? $selectedBroker->phone)
                        <a href="https://wa.me/{{ $selectedBroker->whatsapp ?? $selectedBroker->phone }}" target="_blank"
                           class="flex-1 py-2.5 px-4 rounded-xl bg-tertiary-container text-tertiary-fixed text-xs font-bold flex items-center justify-center gap-2 active:scale-95 transition-transform">
                            <span class="material-symbols-outlined text-[16px]">chat</span>
                            <span>WhatsApp</span>
                        </a>
                    @endif
                    @if($selectedBroker->phone)
                        <a href="tel:{{ $selectedBroker->phone }}"
                           class="flex-1 py-2.5 px-4 rounded-xl bg-primary text-on-primary text-xs font-bold flex items-center justify-center gap-2 shadow-lg active:scale-95 transition-transform">
                            <span class="material-symbols-outlined text-[16px]">call</span>
                            <span>Call Now</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
