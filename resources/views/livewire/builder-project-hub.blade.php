<div>
    {{-- Lister Role Module Switcher --}}
    <section class="px-4 pt-1" x-data="{ activeProfile: 'builder' }">
        <div class="bg-surface-container rounded-xl p-3 space-y-3 shadow-md border border-outline-variant/30">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-primary/15 border border-primary/30 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[16px]">domain</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-[10px] text-on-surface-variant font-medium uppercase tracking-wider">Active Profile</span>
                            <span class="w-1.5 h-1.5 rounded-full bg-tertiary animate-pulse"></span>
                        </div>
                        <h3 class="text-xs font-headline font-bold text-on-surface tracking-tight">Builder / Developer</h3>
                    </div>
                </div>
                <div class="flex items-center gap-1.5">
                    <button wire:click="switchProfile" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-primary/15 hover:bg-primary/25 border border-primary/30 text-primary text-[11px] font-semibold transition-all active:scale-95 shadow-sm">
                        <span class="material-symbols-outlined text-[13px]">swap_horiz</span>
                        <span>Switch Profile</span>
                    </button>
                    <span class="text-[10px] text-tertiary font-semibold hidden sm:flex items-center gap-1 bg-tertiary-container/30 px-2 py-0.5 rounded">
                        <span class="w-1.5 h-1.5 rounded-full bg-tertiary"></span>Enterprise Portal
                    </span>
                </div>
            </div>
            <div class="grid grid-cols-4 gap-1.5 p-1 bg-surface-container-lowest rounded-lg border border-outline-variant/20 transition-all">
                @foreach($profiles ?? [['key' => 'builder', 'icon' => 'verified', 'label' => 'Builder/Dev'], ['key' => 'broker', 'icon' => 'real_estate_agent', 'label' => 'Broker'], ['key' => 'owner', 'icon' => 'person', 'label' => 'Owner'], ['key' => 'agency', 'icon' => 'corporate_fare', 'label' => 'Agency']] as $profile)
                    <button
                        wire:click="setProfile('{{ $profile['key'] }}')"
                        @class([
                            'py-1.5 px-1 rounded-md flex flex-col items-center justify-center gap-0.5 border transition-all',
                            'bg-primary text-on-primary font-bold shadow-sm border-primary' => ($activeProfileKey ?? 'builder') === $profile['key'],
                            'text-on-surface-variant hover:text-on-surface hover:bg-surface-container border-transparent' => ($activeProfileKey ?? 'builder') !== $profile['key'],
                        ])
                    >
                        <div class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-[12px] leading-none">{{ $profile['icon'] }}</span>
                            <span class="text-[10px] leading-tight">{{ $profile['label'] }}</span>
                        </div>
                        <span class="text-[8px] opacity-80 font-mono uppercase tracking-wider font-semibold">
                            {{ ($activeProfileKey ?? 'builder') === $profile['key'] ? 'Active' : 'Switch' }}
                        </span>
                    </button>
                @endforeach
            </div>
            <div class="flex items-center justify-between text-[10px] text-on-surface-variant pt-0.5">
                <p class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-primary text-[13px]">tune</span>
                    <span>Dynamic RERA compliance, inventory tools &amp; team seat allocation.</span>
                </p>
                <span class="text-tertiary font-medium shrink-0 bg-tertiary-container/30 px-1.5 py-0.2 rounded text-[9px]">Enterprise</span>
            </div>
        </div>
    </section>

    {{-- RERA & Regulatory Trust Header --}}
    <section class="px-4">
        <div class="bg-surface-container rounded-xl shadow-md border border-outline-variant/30 overflow-hidden divide-y divide-outline-variant/30">
            {{-- Unified Card Header & Identity Banner --}}
            <div class="p-3.5 space-y-3 bg-surface-container-high/40">
                <div class="space-y-1.5 pb-2 border-b border-outline-variant/30">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider flex items-center gap-1">
                            <span class="material-symbols-outlined text-[13px] text-primary">domain</span>Active Builder Projects ({{ $projects->count() ?? 0 }})
                        </span>
                        <span class="text-[9px] text-primary font-medium flex items-center gap-0.5">
                            <span class="material-symbols-outlined text-[11px]">swap_horiz</span>Tap to switch view
                        </span>
                    </div>
                    <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-1">
                        @foreach($projects as $index => $project)
                            <div wire:click="selectProject({{ $project->id }})"
                                @class([
                                    'min-w-[160px] max-w-[160px] rounded-xl bg-surface-container-lowest cursor-pointer flex flex-col overflow-hidden shrink-0 transition-transform active:scale-95',
                                    'border-2 border-primary ring-2 ring-primary/40 shadow-[0_4px_14px_rgba(167,139,250,0.4)]' => ($selectedProjectId ?? null) === $project->id,
                                    'border border-outline-variant/30 hover:border-outline-variant/60' => ($selectedProjectId ?? null) !== $project->id,
                                ])
                            >
                                <div class="relative h-20 w-full bg-surface-container-highest overflow-hidden">
                                    <img src="{{ $project->getFirstMediaUrl('banner') ?: asset('build/images/property-placeholder.png') }}" alt="{{ $project->name }} preview" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-t from-background/90 via-background/30 to-transparent"></div>
                                    <div class="absolute top-1.5 left-1.5 flex items-center gap-1">
                                        @if($project->status === 'published')
                                            <span class="text-[8px] px-1.5 py-0.5 rounded-full bg-tertiary text-on-tertiary font-bold tracking-wider flex items-center gap-0.5 shadow-sm">
                                                <span class="w-1 h-1 rounded-full bg-on-tertiary animate-pulse"></span>Published
                                            </span>
                                        @else
                                            <span class="text-[8px] px-1.5 py-0.5 rounded-full bg-surface-container-highest text-on-surface-variant font-bold tracking-wider flex items-center gap-0.5 shadow-sm border border-outline-variant/40">
                                                <span class="w-1 h-1 rounded-full bg-secondary"></span>Unpublished
                                            </span>
                                        @endif
                                    </div>
                                    <div class="absolute top-1.5 right-1.5">
                                        <button wire:click.stop="togglePublish({{ $project->id }})" title="Toggle Publish Status" class="w-5 h-5 rounded-full bg-surface/90 hover:bg-surface-bright flex items-center justify-center text-primary backdrop-blur-md transition-colors">
                                            <span class="material-symbols-outlined text-[12px]">{{ $project->status === 'published' ? 'toggle_on' : 'toggle_off' }}</span>
                                        </button>
                                    </div>
                                    @if($project->construction_status)
                                        <div class="absolute bottom-1 left-1.5">
                                            <span class="text-[8px] px-1 py-0.2 rounded bg-tertiary-container/60 text-tertiary font-medium uppercase tracking-wider backdrop-blur-md">{{ $project->construction_status }}</span>
                                        </div>
                                    @endif
                                    <div class="absolute bottom-1 right-1.5">
                                        <span class="text-[8px] font-mono text-on-surface-variant bg-surface/90 px-1 rounded backdrop-blur-md">{{ $project->total_units }} Units</span>
                                    </div>
                                </div>
                                <div class="p-2 flex flex-col justify-between flex-1">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-xs font-headline font-bold text-on-surface truncate">{{ $project->name }}</h4>
                                        @if(($selectedProjectId ?? null) === $project->id)
                                            <span class="text-[8px] text-primary font-bold uppercase tracking-wider bg-primary/10 px-1 rounded">Active</span>
                                        @endif
                                    </div>
                                    <p class="text-[9px] text-on-surface-variant truncate mt-0.5">{{ $project->location }} &bull; {{ $project->tower_count ?? '' }} Towers</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if($selectedProject ?? null)
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-[10px] px-2 py-0.5 rounded font-bold uppercase tracking-wider bg-primary/10 text-primary border border-primary/20">{{ $selectedProject->project_type ?? 'Flagship Township' }}</span>
                            @if($selectedProject->is_sanctioned)
                                <span class="text-[10px] px-2 py-0.5 rounded font-semibold bg-tertiary-container/30 text-tertiary flex items-center gap-0.5">
                                    <span class="material-symbols-outlined text-[12px] leading-none">verified</span> AUDA Sanctioned
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center gap-1.5 px-2 py-0.5 rounded bg-surface-container border border-outline-variant/40">
                            <span class="material-symbols-outlined text-primary text-[14px]">apartment</span>
                            <span class="text-[9px] font-bold text-on-surface-variant uppercase">{{ $selectedProject->tower_count ?? 0 }} Towers &bull; {{ $selectedProject->floor_count ?? 0 }}F</span>
                        </div>
                    </div>
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0 flex-1">
                            <h2 class="text-base font-headline font-bold text-on-surface truncate tracking-tight">{{ $selectedProject->name }}</h2>
                            <p class="text-[11px] text-on-surface-variant">{{ $selectedProject->location }}, {{ $selectedProject->city }}</p>
                        </div>
                    </div>
                    {{-- Compliance Verification Badges --}}
                    <div class="p-2.5 rounded-lg bg-surface-container-lowest space-y-2 text-[11px] border border-outline-variant/20">
                        <div class="flex items-center justify-between gap-1 flex-wrap">
                            <span class="text-on-surface font-mono font-medium flex items-center gap-1">
                                <span class="material-symbols-outlined text-tertiary text-[14px]">gavel</span>
                                RERA: {{ $selectedProject->rera_number ?? 'N/A' }}
                            </span>
                            @if($selectedProject->is_title_verified ?? false)
                                <span class="text-tertiary font-semibold text-[10px] bg-tertiary-container/40 px-2 py-0.5 rounded-full flex items-center gap-0.5">
                                    <span class="material-symbols-outlined text-[11px]">check</span> Verified Title
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center gap-3 text-[10px] text-on-surface-variant flex-wrap pt-0.5">
                            @if($selectedProject->fire_noc_approved ?? false)
                                <span class="flex items-center gap-1 text-on-surface">
                                    <span class="material-symbols-outlined text-tertiary text-[12px]">local_fire_department</span> Fire Safety NOC Approved
                                </span>
                                <span>&bull;</span>
                            @endif
                            @if($selectedProject->structural_clearance ?? false)
                                <span class="flex items-center gap-1 text-on-surface">
                                    <span class="material-symbols-outlined text-primary text-[12px]">shield</span> Structural Clearance
                                </span>
                            @endif
                        </div>
                    </div>
                    {{-- Brochure Download & Instant Action Bar --}}
                    <div class="grid grid-cols-2 gap-2">
                        <a class="h-9 px-2.5 bg-surface-container hover:bg-surface-bright border border-outline-variant/40 text-on-surface rounded-lg text-[11px] font-medium flex items-center justify-center gap-1.5 transition-colors shadow-sm" href="{{ $selectedProject->brochure_url ?? '#' }}">
                            <span class="material-symbols-outlined text-[15px] text-primary">picture_as_pdf</span>
                            <span>Official Brochure</span>
                        </a>
                        <button wire:click="shareDigitalKit({{ $selectedProject->id }})" class="h-9 px-2.5 bg-primary/15 hover:bg-primary/25 border border-primary/30 text-primary rounded-lg text-[11px] font-semibold flex items-center justify-center gap-1.5 transition-colors shadow-sm">
                            <span class="material-symbols-outlined text-[15px]">share</span>
                            <span>Share Digital Kit</span>
                        </button>
                    </div>
                @endif
            </div>

            {{-- Sub-Section 1: Live Stacking Matrix & Inventory --}}
            @if($selectedProject ?? null)
                <div class="p-3.5 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-primary text-[18px]">grid_view</span>
                            <h3 class="text-xs font-headline font-semibold text-on-surface uppercase tracking-wider">Inventory &amp; Stacking Matrix</h3>
                        </div>
                        <div class="flex items-center gap-1.5">
                            @php
                                $soldPercent = $selectedProject->total_units > 0 ? round(($selectedProject->sold_units / $selectedProject->total_units) * 100) : 0;
                            @endphp
                            <span class="text-[10px] font-mono text-tertiary bg-tertiary-container/30 px-2 py-0.5 rounded font-semibold">{{ $soldPercent }}% Sold Out</span>
                            <button wire:click="$toggle('showInventoryModal')" class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-primary/15 hover:bg-primary/25 border border-primary/30 text-primary text-[10px] font-semibold transition-colors active:scale-95">
                                <span class="material-symbols-outlined text-[12px]">edit</span>
                                <span>Edit</span>
                            </button>
                        </div>
                    </div>
                    <div class="grid grid-cols-4 gap-1.5 text-center">
                        <div class="bg-surface-container-lowest p-2 rounded-lg border border-outline-variant/20">
                            <span class="text-xs font-bold text-on-surface block font-mono">{{ $selectedProject->total_units }}</span>
                            <span class="text-[9px] text-on-surface-variant uppercase font-medium">Total Units</span>
                        </div>
                        <div class="bg-surface-container-lowest p-2 rounded-lg border border-outline-variant/20">
                            <span class="text-xs font-bold text-tertiary block font-mono">{{ $selectedProject->sold_units ?? 0 }}</span>
                            <span class="text-[9px] text-on-surface-variant uppercase font-medium">Booked/Sold</span>
                        </div>
                        <div class="bg-surface-container-lowest p-2 rounded-lg border border-outline-variant/20">
                            <span class="text-xs font-bold text-primary block font-mono">{{ $selectedProject->token_hold_units ?? 0 }}</span>
                            <span class="text-[9px] text-on-surface-variant uppercase font-medium">Token Hold</span>
                        </div>
                        <div class="bg-surface-container-lowest p-2 rounded-lg border border-primary/30">
                            <span class="text-xs font-bold block font-mono text-primary">{{ $selectedProject->available_units }}</span>
                            <span class="text-[9px] text-primary uppercase font-bold">Available</span>
                        </div>
                    </div>
                    {{-- Progress Bar --}}
                    <div class="space-y-1">
                        <div class="h-2 w-full bg-surface-container-lowest rounded-full overflow-hidden flex border border-outline-variant/20">
                            <div class="bg-tertiary h-full" style="width: {{ $soldPercent }}%;"></div>
                            <div class="bg-primary h-full" style="width: {{ $selectedProject->total_units > 0 ? round(($selectedProject->token_hold_units ?? 0) / $selectedProject->total_units * 100) : 0 }}%;"></div>
                            <div class="bg-surface-container-high h-full flex-1"></div>
                        </div>
                        <div class="flex items-center justify-between text-[10px] text-on-surface-variant">
                            <span>{{ $selectedProject->sold_units ?? 0 }} Registered Deeds</span>
                            <span>{{ $selectedProject->available_units }} Units Ready for Allotment</span>
                        </div>
                    </div>
                    {{-- Tower Pills --}}
                    @if($towers ?? null)
                        <div class="pt-1 space-y-1.5">
                            <span class="text-[10px] font-semibold text-on-surface-variant uppercase tracking-wider block">Tower-wise Availability ({{ $selectedProject->floor_count ?? 0 }} Floors Each):</span>
                            <div class="grid grid-cols-3 gap-1.5">
                                @foreach($towers as $tower)
                                    <div @class([
                                        'p-2 bg-surface-container-high rounded-lg flex flex-col justify-between',
                                        'border border-primary/20' => $tower->available_units > 0,
                                        'opacity-80' => $tower->available_units === 0,
                                    ])>
                                        <div class="flex items-center justify-between">
                                            <span class="text-[11px] font-bold text-on-surface">{{ $tower->name }}</span>
                                            @if($tower->available_units > 0)
                                                <span class="text-[9px] text-tertiary font-bold bg-tertiary-container/30 px-1 rounded">{{ $tower->available_units }} Left</span>
                                            @else
                                                <span class="text-[9px] text-error font-bold bg-error-container/40 px-1 rounded">Full Sold</span>
                                            @endif
                                        </div>
                                        <p class="text-[9px] text-on-surface-variant mt-0.5">{{ $tower->unit_type }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Sub-Section 2: Media & Marketing Showcase --}}
                <div class="py-3.5 space-y-2.5">
                    <div class="px-3.5 flex items-center justify-between">
                        <div class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-primary text-[18px]">video_library</span>
                            <h3 class="text-xs font-headline font-semibold text-on-surface uppercase tracking-wider">Media &amp; Drone Assets</h3>
                        </div>
                        <button wire:click="manageAssets" class="text-[11px] text-primary hover:text-primary-fixed-dim font-medium">Manage Assets →</button>
                    </div>
                    <div class="flex overflow-x-auto gap-3 px-3.5 no-scrollbar pb-1">
                        @foreach($mediaAssets ?? $projects as $asset)
                            <div class="min-w-[160px] max-w-[160px] rounded-xl bg-surface-container-lowest border border-outline-variant/30 hover:border-outline-variant/60 cursor-pointer flex flex-col overflow-hidden shrink-0 transition-colors active:scale-95">
                                <div class="relative h-20 w-full bg-surface-container-highest overflow-hidden">
                                    <img src="{{ $asset->getFirstMediaUrl('banner') ?: asset('build/images/property-placeholder.png') }}" alt="{{ $asset->name }} preview" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-t from-background/90 via-background/30 to-transparent"></div>
                                    <div class="absolute bottom-1 right-1.5">
                                        <span class="text-[8px] font-mono text-on-surface-variant bg-surface/90 px-1 rounded backdrop-blur-md">{{ $asset->total_units }} Units</span>
                                    </div>
                                </div>
                                <div class="p-2 flex flex-col justify-between flex-1">
                                    <h4 class="text-xs font-headline font-semibold text-on-surface truncate">{{ $asset->name }}</h4>
                                    <p class="text-[9px] text-on-surface-variant truncate mt-0.5">{{ $asset->location }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Sub-Section 3: Specifications & Commercials --}}
                <div class="p-3.5 space-y-3 bg-surface-container-high/20">
                    <div class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-primary text-[18px]">verified_user</span>
                        <h3 class="text-xs font-headline font-semibold text-on-surface uppercase tracking-wider">Specifications &amp; Commercials</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-[11px]">
                        <div class="p-2 bg-surface-container-lowest rounded-lg space-y-1 border border-outline-variant/20">
                            <span class="text-[10px] text-tertiary font-bold flex items-center gap-1">
                                <span class="material-symbols-outlined text-[13px]">storefront</span> {{ $selectedProject->commercial_type ?? 'Retail & Banking Mix' }}
                            </span>
                            <p class="text-[10px] text-on-surface font-semibold">{{ $selectedProject->commercial_detail ?? 'Ground + 2 Floors Commercial' }}</p>
                            <p class="text-[9px] text-on-surface-variant">{{ $selectedProject->commercial_description ?? '' }}</p>
                        </div>
                        <div class="p-2 bg-surface-container-lowest rounded-lg space-y-1 border border-outline-variant/20">
                            <span class="text-[10px] text-primary font-bold flex items-center gap-1">
                                <span class="material-symbols-outlined text-[13px]">pool</span> {{ $selectedProject->amenities_count ?? '45+' }} Tier-1 Amenities
                            </span>
                            <p class="text-[10px] text-on-surface font-semibold">{{ $selectedProject->clubhouse_size ?? '25,000 sq.ft' }} Clubhouse</p>
                            <p class="text-[9px] text-on-surface-variant">{{ $selectedProject->amenities_highlight ?? '' }}</p>
                        </div>
                    </div>
                    <div class="pt-0.5 flex items-center justify-between text-[11px] text-on-surface-variant">
                        <span class="flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-tertiary"></span> Next Inspection: {{ $selectedProject->next_inspection_date ?? 'TBD' }}
                        </span>
                        <button wire:click="viewAuditHistory" class="text-primary hover:text-primary-fixed-dim font-medium text-[10px] flex items-center gap-0.5">
                            Audit History <span class="material-symbols-outlined text-[12px]">arrow_forward</span>
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- Buyer Leads Section --}}
    <section class="px-4 space-y-3" x-data="{ activeTab: 'confirmed', activeChannel: 'all' }">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-1.5">
                <span class="material-symbols-outlined text-tertiary text-[19px]">forum</span>
                <h3 class="text-xs font-headline font-semibold text-on-surface uppercase tracking-wider">Buyer Leads</h3>
            </div>
            <div class="flex items-center gap-1.5 text-[10px] text-on-surface-variant font-medium">
                <span class="w-1.5 h-1.5 rounded-full bg-tertiary animate-pulse"></span>
                <span>{{ $confirmedLeads->count() + $unconfirmedLeads->count() }} Active Leads</span>
            </div>
        </div>

        {{-- Primary Category Tabs --}}
        <div class="grid grid-cols-2 p-1 bg-surface-container rounded-xl gap-1 border border-outline-variant/30 text-xs font-semibold">
            <button
                @click="activeTab = 'confirmed'"
                :class="activeTab === 'confirmed' ? 'py-2 px-2.5 rounded-lg bg-tertiary-container text-on-tertiary-container shadow-sm flex items-center justify-center gap-1.5 transition-colors font-bold text-[11px]' : 'py-2 px-2.5 rounded-lg text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high transition-colors flex items-center justify-center gap-1.5 font-medium text-[11px]'"
            >
                <span class="material-symbols-outlined text-[14px]">verified</span>
                <span>Booking Confirmed</span>
                <span class="px-1.5 py-0.2 rounded-full bg-surface-container-lowest text-tertiary text-[9px] font-mono font-bold">{{ $confirmedLeads->count() }}</span>
            </button>
            <button
                @click="activeTab = 'unconfirmed'"
                :class="activeTab === 'unconfirmed' ? 'py-2 px-2.5 rounded-lg bg-primary/20 text-primary border border-primary/30 shadow-sm flex items-center justify-center gap-1.5 transition-colors font-bold text-[11px]' : 'py-2 px-2.5 rounded-lg text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high transition-colors flex items-center justify-center gap-1.5 font-medium text-[11px]'"
            >
                <span class="material-symbols-outlined text-[14px]">pending_actions</span>
                <span>Not Confirmed</span>
                <span class="px-1.5 py-0.2 rounded-full bg-surface-container-lowest text-primary text-[9px] font-mono font-bold">{{ $unconfirmedLeads->count() }}</span>
            </button>
        </div>

        {{-- Subcategory Filter Pills --}}
        <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar pb-0.5 text-[10px] font-medium">
            <button @click="activeChannel = 'all'" :class="activeChannel === 'all' ? 'px-2.5 py-1 rounded-full bg-surface-container-high text-primary border border-primary/40 shrink-0 font-semibold' : 'px-2.5 py-1 rounded-full bg-surface-container text-on-surface-variant hover:text-on-surface border border-outline-variant/30 shrink-0'">All Channels</button>
            <button @click="activeChannel = 'online'" :class="activeChannel === 'online' ? 'px-2.5 py-1 rounded-full bg-surface-container-high text-primary border border-primary/40 shrink-0 font-semibold flex items-center gap-1' : 'px-2.5 py-1 rounded-full bg-surface-container text-on-surface-variant hover:text-on-surface border border-outline-variant/30 shrink-0 flex items-center gap-1'">
                <span class="material-symbols-outlined text-[12px] text-tertiary">smartphone</span>
                <span>Online Lead (Reels / WhatsApp)</span>
            </button>
            <button @click="activeChannel = 'visit'" :class="activeChannel === 'visit' ? 'px-2.5 py-1 rounded-full bg-surface-container-high text-primary border border-primary/40 shrink-0 font-semibold flex items-center gap-1' : 'px-2.5 py-1 rounded-full bg-surface-container text-on-surface-variant hover:text-on-surface border border-outline-variant/30 shrink-0 flex items-center gap-1'">
                <span class="material-symbols-outlined text-[12px] text-primary">pin_drop</span>
                <span>Physical Site Visit</span>
            </button>
            <button @click="activeChannel = 'token'" class="px-2.5 py-1 rounded-full bg-surface-container text-on-surface-variant hover:text-on-surface border border-outline-variant/30 shrink-0">Token Advance Paid</button>
            <button @click="activeChannel = 'loan'" class="px-2.5 py-1 rounded-full bg-surface-container text-on-surface-variant hover:text-on-surface border border-outline-variant/30 shrink-0">Home Loan Sanctioned</button>
        </div>

        {{-- Confirmed Leads --}}
        <div x-show="activeTab === 'confirmed'" class="space-y-2.5">
            @forelse($confirmedLeads ?? [] as $lead)
                <div class="bg-surface-container p-3.5 rounded-xl shadow-sm space-y-2.5 border border-tertiary-container/40">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex items-center gap-2.5 min-w-0 flex-1">
                            <div class="w-9 h-9 rounded-full bg-tertiary-container/30 border border-tertiary/30 flex items-center justify-center text-xs font-bold text-tertiary shrink-0">
                                {{ strtoupper(substr($lead->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $lead->name)[1] ?? '', 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span class="text-xs font-semibold text-on-surface">{{ $lead->name }}</span>
                                    <span class="text-[9px] px-1.5 py-0.2 rounded bg-primary-container/40 text-on-primary-container font-bold">{{ $lead->unit_detail }} (₹{{ number_format($lead->price / 100000, 2) }} {{ $lead->price >= 10000000 ? 'Cr' : 'L' }})</span>
                                </div>
                                <div class="flex items-center gap-1.5 mt-0.5 flex-wrap">
                                    <span class="inline-flex items-center gap-1 text-[9px] font-medium text-on-surface-variant bg-surface-container-lowest px-1.5 py-0.2 rounded border border-outline-variant/30">
                                        <span class="material-symbols-outlined text-[11px] text-primary">{{ $lead->channel_icon ?? 'directions_walk' }}</span>
                                        <span>{{ $lead->channel_label ?? 'Site Visit' }}</span>
                                    </span>
                                    <span class="text-[9px] text-tertiary font-semibold flex items-center gap-0.5">
                                        <span class="material-symbols-outlined text-[11px]">check_circle</span> {{ $lead->status_label ?? 'Token Advance Paid' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <span class="text-[10px] text-on-surface-variant font-mono shrink-0">{{ $lead->stage ?? 'Agreement Stage' }}</span>
                    </div>
                    @if($lead->notes)
                        <p class="text-[10px] text-on-surface-variant bg-surface-container-lowest p-2 rounded-lg border border-outline-variant/20">
                            {{ $lead->notes }}
                        </p>
                    @endif
                    <div class="grid grid-cols-3 gap-1.5 pt-0.5">
                        <a wire:click="whatsappLead({{ $lead->id }})" class="py-1.5 px-2 bg-tertiary-container text-on-tertiary-container hover:bg-tertiary-container/90 rounded-lg text-[10px] font-semibold flex items-center justify-center gap-1 transition-colors cursor-pointer">
                            <span class="material-symbols-outlined text-[14px]">chat</span> WhatsApp
                        </a>
                        <a wire:click="callLead({{ $lead->id }})" class="py-1.5 px-2 bg-surface-container-high hover:bg-surface-bright text-on-surface rounded-lg text-[10px] font-medium flex items-center justify-center gap-1 transition-colors cursor-pointer">
                            <span class="material-symbols-outlined text-[14px] text-primary">call</span> Call RM
                        </a>
                        <button wire:click="downloadReceipt({{ $lead->id }})" class="py-1.5 px-2 bg-surface-container-high hover:bg-surface-bright text-primary rounded-lg text-[10px] font-medium flex items-center justify-center gap-1 transition-colors">
                            <span class="material-symbols-outlined text-[14px]">receipt_long</span> Receipt PDF
                        </button>
                    </div>
                </div>
            @empty
                <div class="text-center text-[11px] text-on-surface-variant py-4">No confirmed bookings yet.</div>
            @endforelse
        </div>

        {{-- Unconfirmed Leads --}}
        <div x-show="activeTab === 'unconfirmed'" class="space-y-2.5" x-cloak>
            @forelse($unconfirmedLeads ?? [] as $lead)
                <div class="bg-surface-container p-3.5 rounded-xl shadow-sm space-y-2.5 border border-outline-variant/30">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex items-center gap-2.5 min-w-0 flex-1">
                            <div class="w-9 h-9 rounded-full bg-surface-container-highest flex items-center justify-center text-xs font-bold text-primary shrink-0">
                                {{ strtoupper(substr($lead->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $lead->name)[1] ?? '', 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span class="text-xs font-semibold text-on-surface">{{ $lead->name }}</span>
                                    <span class="text-[9px] px-1.5 py-0.2 rounded bg-tertiary-container/30 text-tertiary font-bold">{{ $lead->unit_detail }}</span>
                                </div>
                                <div class="flex items-center gap-1.5 mt-0.5 flex-wrap">
                                    <span class="inline-flex items-center gap-1 text-[9px] font-medium text-on-surface-variant bg-surface-container-lowest px-1.5 py-0.2 rounded border border-outline-variant/30">
                                        <span class="material-symbols-outlined text-[11px] text-tertiary">{{ $lead->channel_icon ?? 'forum' }}</span>
                                        <span>{{ $lead->channel_label ?? 'Online Lead' }}</span>
                                    </span>
                                    <span class="text-[9px] px-1.5 py-0.2 rounded bg-primary/15 text-primary font-bold">{{ $lead->status_label ?? 'Warm' }}</span>
                                </div>
                            </div>
                        </div>
                        <span class="text-[10px] text-on-surface-variant font-mono shrink-0">{{ $lead->time_ago ?? '' }}</span>
                    </div>
                    @if($lead->notes)
                        <p class="text-[10px] text-on-surface-variant bg-surface-container-lowest p-2 rounded-lg border border-outline-variant/20">
                            {{ $lead->notes }}
                        </p>
                    @endif
                    <div class="grid grid-cols-3 gap-1.5 pt-0.5">
                        <a wire:click="whatsappLead({{ $lead->id }})" class="py-1.5 px-2 bg-tertiary-container text-on-tertiary-container hover:bg-tertiary-container/90 rounded-lg text-[10px] font-semibold flex items-center justify-center gap-1 transition-colors cursor-pointer">
                            <span class="material-symbols-outlined text-[14px]">chat</span> WhatsApp
                        </a>
                        <a wire:click="callLead({{ $lead->id }})" class="py-1.5 px-2 bg-surface-container-high hover:bg-surface-bright text-on-surface rounded-lg text-[10px] font-medium flex items-center justify-center gap-1 transition-colors cursor-pointer">
                            <span class="material-symbols-outlined text-[14px] text-primary">call</span> Direct Call
                        </a>
                        <button wire:click="sendGatePass({{ $lead->id }})" class="py-1.5 px-2 bg-surface-container-high hover:bg-surface-bright text-primary rounded-lg text-[10px] font-medium flex items-center justify-center gap-1 transition-colors">
                            <span class="material-symbols-outlined text-[14px]">badge</span> Gate Pass
                        </button>
                    </div>
                </div>
            @empty
                <div class="text-center text-[11px] text-on-surface-variant py-4">No pending leads.</div>
            @endforelse
        </div>
    </section>

    {{-- Inventory Edit Modal --}}
    @if($showInventoryModal ?? false)
        <div class="fixed inset-0 z-50 bg-background/80 backdrop-blur-md flex items-end sm:items-center justify-center p-0 sm:p-4" x-data x-transition>
            <div class="bg-surface-container border border-outline-variant/40 rounded-t-2xl sm:rounded-2xl w-full max-w-lg max-h-[85vh] flex flex-col shadow-2xl overflow-hidden">
                <div class="p-4 border-b border-outline-variant/30 flex items-center justify-between bg-surface-container-high">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-primary/15 border border-primary/30 flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined text-[18px]">edit_note</span>
                        </div>
                        <div>
                            <h3 class="text-sm font-headline font-semibold text-on-surface">Edit Stacking &amp; Tower Matrix</h3>
                            <p class="text-[10px] text-on-surface-variant">{{ $selectedProject->name ?? '' }}</p>
                        </div>
                    </div>
                    <button wire:click="$toggle('showInventoryModal')" class="w-8 h-8 rounded-lg flex items-center justify-center text-on-surface-variant hover:text-on-surface hover:bg-surface-container-highest transition-colors">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>
                <div class="p-4 overflow-y-auto space-y-4">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider block">Global Stacking Status</label>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="p-2 rounded-lg bg-surface-container-lowest border border-outline-variant/30">
                                <span class="text-[9px] text-on-surface-variant block uppercase font-medium">Total Units</span>
                                <input wire:model="editTotalUnits" type="number" class="w-full bg-transparent text-xs font-mono font-bold text-on-surface focus:outline-none mt-0.5">
                            </div>
                            <div class="p-2 rounded-lg bg-surface-container-lowest border border-outline-variant/30">
                                <span class="text-[9px] text-tertiary block uppercase font-medium">Sold/Booked</span>
                                <input wire:model="editSoldUnits" type="number" class="w-full bg-transparent text-xs font-mono font-bold text-tertiary focus:outline-none mt-0.5">
                            </div>
                            <div class="p-2 rounded-lg bg-surface-container-lowest border border-primary/40">
                                <span class="text-[9px] text-primary block uppercase font-medium">Available</span>
                                <input wire:model="editAvailableUnits" type="number" class="w-full bg-transparent text-xs font-mono font-bold text-primary focus:outline-none mt-0.5">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-3.5 border-t border-outline-variant/30 bg-surface-container-high flex items-center justify-end gap-2">
                    <button wire:click="$toggle('showInventoryModal')" class="px-3 py-1.5 rounded-lg border border-outline-variant/40 text-[11px] font-medium text-on-surface hover:bg-surface-bright transition-colors">Cancel</button>
                    <button wire:click="saveInventory" class="px-3.5 py-1.5 rounded-lg bg-primary hover:bg-primary-fixed-dim text-on-primary text-[11px] font-semibold flex items-center gap-1 transition-colors shadow-sm">
                        <span class="material-symbols-outlined text-[13px]">save</span>Save &amp; Update Matrix
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
