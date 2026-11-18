<div>
    {{-- Role Mode Switcher Bar --}}
    <div class="w-full overflow-x-auto scrollbar-none py-1" x-data="{ activeMode: '{{ $activeMode ?? 'owner' }}' }">
        <div class="flex items-center gap-2 min-w-max px-0.5">
            @foreach([
                ['key' => 'builder', 'label' => 'Builder/Dev'],
                ['key' => 'broker', 'label' => 'Broker Hub'],
                ['key' => 'owner', 'label' => 'Owner Direct', 'badge' => '0% BROKERAGE'],
                ['key' => 'agency', 'label' => 'Agency'],
            ] as $mode)
                <button
                    wire:click="switchMode('{{ $mode['key'] }}')"
                    @click="activeMode = '{{ $mode['key'] }}'"
                    :class="activeMode === '{{ $mode['key'] }}' ? 'px-3.5 py-1.5 rounded-full text-xs font-semibold font-label bg-surface-container-highest text-primary flex items-center gap-1.5' : 'px-3 py-1.5 rounded-full text-xs font-label text-on-surface-variant bg-surface-container hover:bg-surface-container-high transition-colors'"
                >
                    <template x-if="activeMode === '{{ $mode['key'] }}'">
                        <span class="w-1.5 h-1.5 rounded-full bg-tertiary"></span>
                    </template>
                    {{ $mode['label'] }}
                    @if($mode['badge'] ?? false)
                        <template x-if="activeMode === '{{ $mode['key'] }}'">
                            <span class="text-[9px] px-1.5 py-0.2 rounded-full bg-tertiary-container text-tertiary font-bold tracking-tight">{{ $mode['badge'] }}</span>
                        </template>
                    @endif
                </button>
            @endforeach
        </div>
    </div>

    {{-- Verified Owner Guarantee Banner --}}
    <div class="w-full bg-surface-container p-3 rounded-lg flex items-center gap-3">
        <div class="w-8 h-8 rounded-full bg-tertiary-container flex items-center justify-center shrink-0 text-tertiary">
            <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">verified_user</span>
        </div>
        <div class="flex flex-col min-w-0">
            <div class="flex items-center gap-1.5">
                <span class="text-xs font-headline font-bold text-on-surface">Individual Properties</span>
            </div>
            <p class="text-[11px] text-on-surface-variant leading-tight truncate">Sell&nbsp; directly with genuine vetted buyers</p>
        </div>
    </div>

    {{-- Primary Buy / Sell Mode Toggle --}}
    <div class="flex flex-col gap-1.5 bg-surface-container p-1.5 rounded-lg" x-data="{ sellMode: 'sell' }">
        <div class="grid grid-cols-2 gap-1.5">
            <button
                @click="sellMode = 'sell'"
                :class="sellMode === 'sell' ? 'py-2.5 px-3 rounded-lg bg-surface-container-highest text-on-surface flex items-center justify-center gap-1.5 text-xs font-label font-bold transition-all' : 'py-2.5 px-3 rounded-lg text-on-surface-variant hover:text-on-surface flex items-center justify-center gap-1.5 text-xs font-label font-medium transition-all'"
            >
                <span class="material-symbols-outlined text-sm text-primary">real_estate_agent</span>
                Sell Property <span class="text-[10px] text-primary bg-surface-container px-1 rounded-full">{{ $myListingsCount ?? 0 }}</span>
            </button>
            <button
                @click="sellMode = 'explore'"
                :class="sellMode === 'explore' ? 'py-2.5 px-3 rounded-lg bg-surface-container-highest text-on-surface flex items-center justify-center gap-1.5 text-xs font-label font-bold transition-all' : 'py-2.5 px-3 rounded-lg text-on-surface-variant hover:text-on-surface flex items-center justify-center gap-1.5 text-xs font-label font-medium transition-all'"
            >
                <span class="material-symbols-outlined text-sm">storefront</span>
                Explore nearby
            </button>
        </div>
    </div>

    {{-- Quick Action Hub / Sell Flow Launcher --}}
    <div class="w-full bg-surface-container rounded-lg p-3.5 space-y-3">
        <div class="flex items-start justify-between gap-2">
            <div class="space-y-0.5">
                <div class="flex items-center gap-1.5">
                    <span class="text-sm font-headline font-bold text-on-surface tracking-tight">+ List New Property Free</span>
                </div>
            </div>
            <div class="w-7 h-7 rounded-full bg-primary/10 flex items-center justify-center text-primary shrink-0">
                <span class="material-symbols-outlined text-base">auto_awesome</span>
            </div>
        </div>
        {{-- 3-Column Quick Media Ingest Grid --}}
        <div class="grid grid-cols-3 gap-2">
            <button wire:click="startWalkthrough" class="flex flex-col items-center justify-center p-2.5 rounded-lg bg-surface-container-high hover:bg-surface-container-highest transition-colors group text-center space-y-1">
                <div class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center text-primary group-hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined text-lg">videocam</span>
                </div>
                <span class="text-[11px] font-label font-bold text-on-surface">Camera&nbsp;</span>
                <span class="text-[9px] text-on-surface-variant leading-none">Walkthrough</span>
            </button>
            <button wire:click="uploadPhotos" class="flex flex-col items-center justify-center p-2.5 rounded-lg bg-surface-container-high hover:bg-surface-container-highest transition-colors group text-center space-y-1">
                <div class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center text-tertiary group-hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined text-lg">add_photo_alternate</span>
                </div>
                <span class="text-[11px] font-label font-bold text-on-surface">Photos &amp; Videos</span>
                <span class="text-[9px] text-on-surface-variant leading-none">Rooms &amp; Sketch</span>
            </button>
            <button wire:click="uploadDocs" class="flex flex-col items-center justify-center p-2.5 rounded-lg bg-surface-container-high hover:bg-surface-container-highest transition-colors group text-center space-y-1">
                <div class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center text-secondary-fixed group-hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined text-lg">verified</span>
                </div>
                <span class="text-[11px] font-label font-bold text-on-surface">Legal Docs</span>
                <span class="text-[9px] text-on-surface-variant leading-none">7/12 &amp; NOC</span>
            </button>
        </div>
    </div>

    {{-- Active Properties Listed by Owner --}}
    <div class="space-y-3">
        <div class="flex items-center justify-between px-0.5">
            <div class="flex items-center gap-2">
                <h2 class="text-sm font-headline font-bold text-on-surface tracking-tight">My Active Listings</h2>
                <span class="text-[10px] font-bold text-on-surface bg-surface-container-highest px-1.5 py-0.5 rounded">{{ $listings->count() ?? 0 }}</span>
            </div>
            <span class="flex items-center gap-1.5 text-[11px] font-label font-semibold text-tertiary">
                <span class="w-2 h-2 rounded-full bg-tertiary"></span>
                Live on Video Feed
            </span>
        </div>

        @foreach($listings ?? [] as $listing)
            {{-- Listing Card --}}
            <div class="bg-surface-container rounded-lg p-3 space-y-3">
                <div class="relative w-full h-36 rounded-lg overflow-hidden">
                    <img class="w-full h-full object-cover" src="{{ $listing->getFirstMediaUrl('images') ?: asset('build/images/property-placeholder.png') }}" alt="{{ $listing->title }}">
                    <div class="absolute inset-0 bg-gradient-to-t from-surface via-transparent to-transparent opacity-90"></div>
                    @if($listing->is_verified)
                        <div class="absolute top-2 left-2 flex items-center gap-1 bg-surface/85 backdrop-blur-md px-2 py-0.5 rounded text-[10px] font-bold text-tertiary">
                            <span class="material-symbols-outlined text-[13px]" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                            Verified Owner
                        </div>
                    @endif
                    @if($listing->reel_duration)
                        <div class="absolute top-2 right-2 flex items-center gap-1 bg-surface-container-highest/90 backdrop-blur-md px-2 py-0.5 rounded text-[10px] font-mono text-on-surface">
                            <span class="material-symbols-outlined text-[12px] text-primary">play_arrow</span>
                            {{ $listing->reel_duration }} 4K Reel
                        </div>
                    @endif
                    <div class="absolute bottom-2 left-2 right-2 flex items-baseline justify-between">
                        <div>
                            <div class="text-base font-headline font-bold text-on-surface leading-tight">₹{{ number_format($listing->price / 100000, 1) }} Lakh</div>
                            <div class="text-[10px] font-label text-tertiary font-semibold uppercase">Zero Brokerage Direct Deal</div>
                        </div>
                        <span class="text-[10px] font-mono text-on-surface-variant bg-surface-container-high/80 px-1.5 py-0.5 rounded">ID: {{ $listing->listing_code ?? 'REEL-' . $listing->id }}</span>
                    </div>
                </div>
                <div class="space-y-1">
                    <h3 class="text-xs font-headline font-bold text-on-surface truncate">{{ $listing->title }}</h3>
                    <p class="text-[11px] text-on-surface-variant flex items-center gap-1">
                        <span class="material-symbols-outlined text-xs">location_on</span> {{ $listing->locality ?? '' }} &bull; {{ number_format($listing->carpet_area_sqft ?? 0) }} sq.ft &bull; {{ $listing->possession_status ?? 'Ready to Move' }}
                    </p>
                </div>
                {{-- Live Listing Performance Strip --}}
                <div class="grid grid-cols-3 gap-1 bg-surface-container-high p-2 rounded-lg text-center">
                    <div>
                        <div class="text-xs font-mono font-bold text-on-surface">{{ number_format($listing->reel_views ?? 0) }}</div>
                        <div class="text-[9px] text-on-surface-variant uppercase font-label">Reel Views</div>
                    </div>
                    <div>
                        <div class="text-xs font-mono font-bold text-primary">{{ $listing->direct_chats ?? 0 }}</div>
                        <div class="text-[9px] text-on-surface-variant uppercase font-label">Direct Chats</div>
                    </div>
                    <div>
                        <div class="text-xs font-mono font-bold text-tertiary">{{ $listing->site_visits ?? 0 }}</div>
                        <div class="text-[9px] text-on-surface-variant uppercase font-label">Site Visits</div>
                    </div>
                </div>
                {{-- Card Action Buttons --}}
                <div class="grid grid-cols-3 gap-1.5 pt-0.5">
                    <button wire:click="viewDocs({{ $listing->id }})" class="py-1.5 px-2 rounded bg-surface-container-highest hover:bg-surface-bright text-[11px] font-label font-medium text-on-surface flex items-center justify-center gap-1">
                        <span class="material-symbols-outlined text-xs text-on-surface-variant">folder_open</span> Docs
                    </button>
                    <button wire:click="boostListing({{ $listing->id }})" class="py-1.5 px-2 rounded bg-primary-container hover:bg-primary-container/80 text-[11px] font-label font-bold text-on-primary-container flex items-center justify-center gap-1">
                        <span class="material-symbols-outlined text-xs">rocket_launch</span> Boost
                    </button>
                    <button wire:click="markSold({{ $listing->id }})" class="py-1.5 px-2 rounded bg-surface-container-high hover:bg-surface-container-highest text-[11px] font-label font-medium text-on-surface-variant hover:text-on-surface flex items-center justify-center gap-1">
                        <span class="material-symbols-outlined text-xs">done_all</span>Mark as Sold
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Direct Buyer Inquiries & Contact Center --}}
    <div class="space-y-3">
        <div class="flex items-center justify-between px-0.5">
            <div class="flex items-center gap-1.5">
                <h2 class="text-sm font-headline font-bold text-on-surface tracking-tight">Direct Buyer Inquiries</h2>
                <span class="text-[10px] font-bold bg-primary text-on-primary px-1.5 py-0.2 rounded-full">{{ $newInquiriesCount ?? 0 }} New</span>
            </div>
            <span class="text-[10px] text-on-surface-variant font-label">No Agents &bull; Verified Only</span>
        </div>

        @foreach($inquiries ?? [] as $inquiry)
            <div class="bg-surface-container rounded-lg p-3 space-y-2.5">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-surface-container-highest flex items-center justify-center font-headline font-bold text-xs text-primary">
                            {{ strtoupper(substr($inquiry->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $inquiry->name)[1] ?? '', 0, 1)) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-1">
                                <span class="text-xs font-headline font-bold text-on-surface">{{ $inquiry->name }}</span>
                                @if($inquiry->is_verified)
                                    <span class="material-symbols-outlined text-[13px] text-tertiary" style="font-variation-settings: 'FILL' 1;">verified</span>
                                @endif
                                @if($inquiry->badge)
                                    <span class="text-[9px] font-bold bg-tertiary-container text-tertiary px-1 rounded">{{ $inquiry->badge }}</span>
                                @endif
                            </div>
                            <div class="text-[9px] text-on-surface-variant">{{ $inquiry->subtitle ?? '' }}</div>
                        </div>
                    </div>
                    <span class="text-[10px] font-mono text-on-surface-variant">{{ $inquiry->time_ago }}</span>
                </div>
                @if($inquiry->message)
                    <div class="bg-surface-container-high p-2 rounded text-[11px] text-on-surface leading-snug">
                        "{{ $inquiry->message }}"
                    </div>
                @endif
                @if($inquiry->direct_offer)
                    <div class="text-[9px] text-on-surface-variant">Direct Offer: <span class="text-on-surface font-semibold">₹{{ number_format($inquiry->direct_offer / 100000, 1) }} Lakh</span></div>
                @endif
                <div class="grid grid-cols-{{ $inquiry->show_gate_pass ? '3' : '2' }} gap-1.5">
                    <button wire:click="whatsappInquiry({{ $inquiry->id }})" class="py-1.5 px-2 rounded bg-tertiary text-on-tertiary text-[11px] font-label font-bold flex items-center justify-center gap-1 hover:opacity-95 transition-opacity">
                        <span class="material-symbols-outlined text-xs">chat</span> WhatsApp
                    </button>
                    <button wire:click="callInquiry({{ $inquiry->id }})" class="py-1.5 px-2 rounded bg-surface-container-highest hover:bg-surface-bright text-[11px] font-label font-bold text-on-surface flex items-center justify-center gap-1">
                        <span class="material-symbols-outlined text-xs">call</span> Direct Call
                    </button>
                    @if($inquiry->show_gate_pass ?? false)
                        <button wire:click="issueGatePass({{ $inquiry->id }})" class="py-1.5 px-2 rounded bg-primary-container text-on-primary-container text-[11px] font-label font-bold flex items-center justify-center gap-1 hover:opacity-95 transition-opacity">
                            <span class="material-symbols-outlined text-xs">vpn_key</span> Gate Pass
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    {{-- Document & Title Trust Vault --}}
    <div class="bg-surface-container rounded-lg p-3.5 space-y-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-full bg-tertiary/10 flex items-center justify-center text-tertiary">
                    <span class="material-symbols-outlined text-base">shield</span>
                </div>
                <div>
                    <h3 class="text-xs font-headline font-bold text-on-surface">Title &amp; Trust Vault</h3>
                    <p class="text-[10px] text-on-surface-variant">Self-Service Document Verification</p>
                </div>
            </div>
            <div class="flex items-center gap-1 bg-surface-container-high px-2 py-0.5 rounded text-[11px] font-mono font-bold text-tertiary">
                {{ $verificationPercent ?? 95 }}% Verified
            </div>
        </div>
        {{-- Verification Progress Checklist --}}
        <div class="space-y-1.5 text-xs">
            @foreach($documents ?? [] as $doc)
                <div class="flex items-center justify-between p-2 rounded bg-surface-container-high">
                    <div class="flex items-center gap-2">
                        @if($doc->is_verified)
                            <span class="material-symbols-outlined text-sm text-tertiary" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                        @else
                            <span class="material-symbols-outlined text-sm text-secondary-fixed">pending</span>
                        @endif
                        <span class="{{ $doc->is_verified ? 'text-on-surface' : 'text-on-surface-variant' }} text-[11px]">{{ $doc->name }}</span>
                    </div>
                    @if($doc->is_verified)
                        <span class="text-[10px] font-mono text-tertiary">Verified</span>
                    @else
                        <button wire:click="uploadDocument({{ $doc->id }})" class="text-[10px] font-label font-bold text-primary hover:underline">+ Upload</button>
                    @endif
                </div>
            @endforeach
        </div>
        <div class="text-[10px] text-on-surface-variant bg-surface-container-low p-2 rounded flex items-start gap-1.5">
            <span class="material-symbols-outlined text-xs text-primary shrink-0 mt-0.5">tips_and_updates</span>
            <span>Verified documents boost your listing impressions by 3.5x and prioritize direct genuine buyers.</span>
        </div>
    </div>

    {{-- Direct Buy Quick Recommendations --}}
    <div class="space-y-2.5">
        <div class="flex items-center justify-between px-0.5">
            <div>
                <h2 class="text-xs font-headline font-bold text-on-surface">Looking to Buy Next Property? Explore Direct Resale</h2>
                <p class="text-[10px] text-on-surface-variant">Owner-to-Owner Deals &bull; Zero Brokerage</p>
            </div>
            <button wire:click="viewAllResale" class="text-[11px] font-label text-primary font-semibold hover:underline">View All</button>
        </div>
        <div class="grid grid-cols-2 gap-2">
            @foreach($recommendations ?? [] as $rec)
                <div class="bg-surface-container rounded-lg p-2 space-y-2 flex flex-col justify-between">
                    <div class="relative w-full h-20 rounded overflow-hidden">
                        <img class="w-full h-full object-cover" src="{{ $rec->getFirstMediaUrl('images') ?: asset('build/images/property-placeholder.png') }}" alt="{{ $rec->title }}">
                        <div class="absolute top-1 left-1 bg-surface/80 backdrop-blur-sm px-1 py-0.2 rounded text-[8px] font-bold text-tertiary">0% COMM</div>
                    </div>
                    <div>
                        <div class="text-xs font-headline font-bold text-on-surface leading-tight">₹{{ number_format($rec->price / 100000, 1) }} Lakh</div>
                        <div class="text-[10px] text-on-surface-variant truncate">{{ $rec->bhk_type ?? '' }} &bull; {{ $rec->locality ?? '' }}</div>
                    </div>
                    <button wire:click="whatsappOwner({{ $rec->id }})" class="w-full py-1 rounded bg-surface-container-high hover:bg-surface-container-highest text-[10px] font-label font-semibold text-tertiary flex items-center justify-center gap-1">
                        <span class="material-symbols-outlined text-xs">chat</span> WhatsApp Owner
                    </button>
                </div>
            @endforeach
        </div>
    </div>
</div>
