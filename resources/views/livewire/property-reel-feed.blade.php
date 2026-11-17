<div class="flex flex-col w-full h-[calc(100vh-4rem)] overflow-y-scroll snap-y snap-mandatory no-scrollbar select-none" style="scroll-snap-type: y mandatory;">
    {{-- Streamlined Search & Filter Header Strip --}}
    <div class="sticky top-0 z-30 bg-background/95 backdrop-blur-md px-4 pt-2.5 pb-2 flex flex-col gap-2 border-b border-outline-variant/40">
        <div class="relative flex items-center w-full">
            <div class="absolute left-3 flex items-center pointer-events-none text-primary">
                <span class="material-symbols-outlined text-[18px]">search</span>
            </div>
            <input wire:model.live.debounce.300ms="search"
                   class="w-full bg-surface-container text-on-surface text-xs font-normal pl-9 pr-20 py-2 rounded-xl outline-none placeholder:text-on-surface-variant focus:bg-surface-container-high transition-colors tracking-tight"
                   placeholder="Search 2 BHK, SG Highway, Auctions..." type="text">
            <div class="absolute right-1.5 flex items-center gap-1">
                <button aria-label="AI Voice Search"
                        class="w-7 h-7 rounded-lg flex items-center justify-center text-on-surface-variant hover:text-tertiary transition-colors bg-surface-container-high/60 active:scale-95">
                    <span class="material-symbols-outlined text-[16px]">mic</span>
                </button>
                <button wire:click="$toggle('showFilters')" aria-label="Open Filters"
                        class="w-7 h-7 rounded-lg flex items-center justify-center text-on-surface-variant hover:text-primary transition-colors bg-surface-container-high/60 active:scale-95 relative">
                    <span class="material-symbols-outlined text-[16px]">tune</span>
                    @if(count($activeFilters ?? []) > 0)
                        <span class="absolute top-1 right-1 w-1.5 h-1.5 rounded-full bg-primary"></span>
                    @endif
                </button>
            </div>
        </div>
        <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar -mx-4 px-4">
            <button wire:click="$set('filter', 'all')"
                    class="px-3 py-1.5 rounded-full text-[11px] font-semibold whitespace-nowrap shadow-sm flex items-center gap-1 active:scale-95 transition-transform
                    {{ ($filter ?? 'all') === 'all' ? 'bg-primary text-on-primary' : 'bg-surface-container text-on-surface' }}">
                <span class="material-symbols-outlined text-[14px]">tune</span>
                <span>All ({{ number_format($totalCount ?? 0) }})</span>
            </button>
            @foreach($quickFilters ?? [] as $qf)
                <button wire:click="applyQuickFilter('{{ $qf['key'] }}')"
                        class="px-2.5 py-1.5 rounded-full text-[11px] font-medium whitespace-nowrap flex items-center gap-1 border border-outline-variant/30 active:scale-95 transition-colors
                        {{ ($qf['active'] ?? false) ? 'bg-tertiary-container/30 border-tertiary/40 text-tertiary font-semibold' : 'bg-surface-container text-on-surface hover:bg-surface-container-high' }}">
                    @if($qf['icon'] ?? false)
                        <span class="material-symbols-outlined text-[13px] {{ $qf['iconColor'] ?? 'text-primary' }}">{{ $qf['icon'] }}</span>
                    @endif
                    <span>{{ $qf['label'] }}</span>
                    @if($qf['badge'] ?? false)
                        <span class="text-[9px] bg-tertiary text-on-tertiary font-bold px-1 rounded-full">{{ $qf['badge'] }}</span>
                    @endif
                </button>
            @endforeach
        </div>
    </div>

    {{-- Comprehensive Filter Bottom Sheet --}}
    <div x-data="{ showFilter: @entangle('showFilters') }">
        <div x-show="showFilter" x-transition.opacity class="fixed inset-0 bg-background/80 backdrop-blur-sm z-50" @click="showFilter = false">
            <div class="absolute bottom-0 left-0 right-0 max-h-[85vh] bg-surface-container-lowest border-t border-outline-variant/40 rounded-t-2xl shadow-2xl flex flex-col overflow-hidden pb-safe" @click.stop>
                {{-- Drawer Header --}}
                <div class="px-4 pt-3 pb-2.5 flex items-center justify-between border-b border-outline-variant/30 bg-surface-container-low/80 backdrop-blur-md">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-primary/20 flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined text-[18px]">tune</span>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-on-surface leading-tight">Filter Properties</h3>
                            <p class="text-[10px] text-on-surface-variant">{{ number_format($totalCount ?? 0) }} verified homes in {{ $locationLabel ?? 'your area' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button wire:click="resetFilters" class="text-[11px] font-semibold text-primary hover:underline px-2 py-1">Reset</button>
                        <button @click="showFilter = false" aria-label="Close Filter Drawer"
                                class="w-8 h-8 rounded-full bg-surface-container hover:bg-surface-container-high flex items-center justify-center text-on-surface-variant hover:text-on-surface transition-colors">
                            <span class="material-symbols-outlined text-[18px]">close</span>
                        </button>
                    </div>
                </div>
                {{-- Filter Body --}}
                <div class="flex-1 overflow-y-auto px-4 py-3 flex flex-col gap-4 no-scrollbar max-h-[calc(85vh-8rem)]">
                    {{-- Budget Filter --}}
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-semibold text-on-surface flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[15px] text-primary">payments</span>
                                Budget Range
                            </label>
                            <span class="text-[11px] font-bold text-primary">{{ $budgetLabel ?? 'Any' }}</span>
                        </div>
                        <div class="grid grid-cols-4 gap-1.5">
                            @foreach($budgetRanges ?? [] as $range)
                                <button wire:click="setBudget('{{ $range['key'] }}')"
                                        class="px-2 py-1.5 rounded-lg text-[11px] font-medium text-center active:scale-95 transition-colors
                                        {{ ($budget ?? '') === $range['key'] ? 'bg-primary text-on-primary font-bold shadow-sm' : 'bg-surface-container text-on-surface-variant hover:text-on-surface border border-outline-variant/30' }}">
                                    {{ $range['label'] }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Possession Status --}}
                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-semibold text-on-surface flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[15px] text-primary">domain</span>
                            Possession Status
                        </label>
                        <div class="grid grid-cols-3 gap-1.5">
                            @foreach(['ready' => ['icon' => 'key', 'label' => 'Ready to Move'], 'soon' => ['icon' => 'construction', 'label' => 'In 6 Months'], 'under_construction' => ['icon' => 'engineering', 'label' => 'Under Const.']] as $key => $opt)
                                <button wire:click="setPossession('{{ $key }}')"
                                        class="px-2.5 py-1.5 rounded-lg text-[11px] font-medium text-center flex items-center justify-center gap-1
                                        {{ ($possession ?? '') === $key ? 'bg-primary/20 border border-primary/40 text-primary font-semibold shadow-sm' : 'bg-surface-container text-on-surface-variant hover:text-on-surface border border-outline-variant/30' }}">
                                    <span class="material-symbols-outlined text-[13px]">{{ $opt['icon'] }}</span>
                                    {{ $opt['label'] }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Furnishing --}}
                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-semibold text-on-surface flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[15px] text-primary">chair</span>
                            Furnishing
                        </label>
                        <div class="grid grid-cols-3 gap-1.5">
                            @foreach(['fully' => 'Fully Furnished', 'semi' => 'Semi-Furnished', 'unfurnished' => 'Unfurnished'] as $key => $label)
                                <button wire:click="setFurnishing('{{ $key }}')"
                                        class="px-2.5 py-1.5 rounded-lg text-[11px] font-medium text-center
                                        {{ ($furnishing ?? '') === $key ? 'bg-surface-container-highest text-primary border border-primary/40 font-semibold' : 'bg-surface-container text-on-surface-variant hover:text-on-surface border border-outline-variant/30' }}">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
                {{-- Apply Footer --}}
                <div class="p-4 border-t border-outline-variant/30 bg-surface-container-low flex items-center gap-2">
                    <button wire:click="applyFilters" @click="showFilter = false"
                            class="flex-1 py-2.5 px-4 rounded-xl bg-primary text-on-primary text-xs font-bold flex items-center justify-center gap-2 shadow-lg active:scale-95 transition-transform">
                        <span class="material-symbols-outlined text-[16px]">done_all</span>
                        <span>Apply Filters ({{ number_format($totalCount ?? 0) }} Homes)</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Reel Slides --}}
    @foreach($properties ?? [] as $property)
        <article class="w-full h-[calc(100vh-8.5rem)] min-h-[580px] snap-start snap-always flex flex-col relative bg-background border-b border-outline-variant/30 flex-shrink-0">
            {{-- Primary Image/Video Hero (~58% Height) --}}
            <div class="relative w-full h-[58%] bg-surface-container-lowest overflow-hidden">
                <img alt="{{ $property->title }}"
                     class="w-full h-full object-cover"
                     src="{{ $property->getFirstMediaUrl('images') ?: asset('build/images/property-placeholder.png') }}">
                <div class="absolute inset-0 bg-gradient-to-t from-background via-transparent to-background/50"></div>

                {{-- Top Badges Overlay --}}
                <div class="absolute top-3 left-3 flex items-center gap-1.5 z-10">
                    @if($property->media->where('type', 'reel')->count())
                        <div class="px-2.5 py-1 rounded-lg bg-surface-container-lowest/85 backdrop-blur-md text-on-primary-container flex items-center gap-1 text-[11px] font-medium shadow-md">
                            <span class="material-symbols-outlined text-[14px] text-primary" style="font-variation-settings: 'FILL' 1;">videocam</span>
                            <span>{{ $property->media->where('type', 'reel')->first()->duration ?? '24s' }} Walkthrough Reel</span>
                        </div>
                    @endif
                    @if($property->rera_number)
                        <div class="px-2 py-1 rounded-lg bg-tertiary-container text-tertiary-fixed text-[10px] font-bold flex items-center gap-0.5 shadow-sm">
                            <span class="material-symbols-outlined text-[12px]">verified</span>
                            <span>RERA Ready</span>
                        </div>
                    @endif
                    @if($property->auction)
                        <div class="px-2.5 py-1 rounded-lg bg-error-container text-on-error-container flex items-center gap-1.5 text-[11px] font-bold shadow-md">
                            <span class="w-2 h-2 rounded-full bg-error animate-ping"></span>
                            <span>Live Auction in {{ $property->auction->days_remaining }} Days ({{ $property->auction->bank_name }})</span>
                        </div>
                    @endif
                </div>

                {{-- Top-Right Media Counter --}}
                <div class="absolute top-3 right-3 z-10 px-2 py-0.5 rounded-full bg-surface-container-lowest/80 backdrop-blur-md text-on-surface text-[10px] font-medium">
                    1 / {{ $property->media->count() ?: 1 }} Media
                </div>

                {{-- Center Play Button --}}
                @if($property->media->where('type', 'reel')->count())
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <div class="w-12 h-12 rounded-full bg-surface-container-highest/80 backdrop-blur-md text-primary flex items-center justify-center shadow-xl">
                            <span class="material-symbols-outlined text-[28px] ml-0.5" style="font-variation-settings: 'FILL' 1;">play_arrow</span>
                        </div>
                    </div>
                @endif

                {{-- Instagram-Style Vertical Action Rail --}}
                <div class="absolute right-2.5 bottom-16 z-20 flex flex-col items-center gap-3">
                    <button wire:click="toggleLike({{ $property->id }})" aria-label="Like property" class="flex flex-col items-center gap-0.5 text-on-surface active:scale-90 transition-transform">
                        <div class="w-9 h-9 rounded-full bg-surface-container-lowest/70 backdrop-blur-md flex items-center justify-center hover:text-error transition-colors">
                            <span class="material-symbols-outlined text-[20px] {{ in_array($property->id, $likedProperties ?? []) ? 'text-error' : '' }}"
                                  @if(in_array($property->id, $likedProperties ?? [])) style="font-variation-settings: 'FILL' 1;" @endif>favorite</span>
                        </div>
                        <span class="text-[10px] font-bold text-on-surface leading-tight">{{ $property->likes_count_formatted ?? '0' }}</span>
                    </button>
                    <button wire:click="showComments({{ $property->id }})" aria-label="Comments" class="flex flex-col items-center gap-0.5 text-on-surface active:scale-90 transition-transform">
                        <div class="w-9 h-9 rounded-full bg-surface-container-lowest/70 backdrop-blur-md flex items-center justify-center hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[20px]">chat_bubble</span>
                        </div>
                        <span class="text-[10px] font-bold text-on-surface leading-tight">{{ $property->comments_count ?? 0 }}</span>
                    </button>
                    <button wire:click="toggleBookmark({{ $property->id }})" aria-label="Bookmark" class="flex flex-col items-center gap-0.5 text-on-surface active:scale-90 transition-transform">
                        <div class="w-9 h-9 rounded-full bg-surface-container-lowest/70 backdrop-blur-md flex items-center justify-center hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[20px] {{ in_array($property->id, $bookmarkedProperties ?? []) ? 'text-primary' : '' }}"
                                  @if(in_array($property->id, $bookmarkedProperties ?? [])) style="font-variation-settings: 'FILL' 1;" @endif>bookmark</span>
                        </div>
                        <span class="text-[10px] font-bold text-on-surface leading-tight">{{ $property->bookmarks_count_formatted ?? '0' }}</span>
                    </button>
                    <button wire:click="shareProperty({{ $property->id }})" aria-label="Share reel" class="flex flex-col items-center gap-0.5 text-on-surface active:scale-90 transition-transform">
                        <div class="w-9 h-9 rounded-full bg-surface-container-lowest/70 backdrop-blur-md flex items-center justify-center hover:text-tertiary transition-colors">
                            <span class="material-symbols-outlined text-[20px]">send</span>
                        </div>
                        <span class="text-[10px] font-bold text-on-surface leading-tight">Share</span>
                    </button>
                </div>

                {{-- Bottom Gallery Thumbnail Rail --}}
                <div class="absolute bottom-2 left-0 right-14 z-10 px-3 flex items-center gap-1.5 overflow-x-auto no-scrollbar">
                    @foreach($property->media->take(4) as $index => $media)
                        <div class="relative flex-shrink-0 w-14 h-11 rounded-lg overflow-hidden {{ $index === 0 ? 'ring-2 ring-primary shadow-lg' : 'opacity-70 hover:opacity-100 transition-opacity' }} cursor-pointer">
                            @if($media->thumbnail_url)
                                <img alt="{{ $media->caption ?? 'Media '.$loop->iteration }}" class="w-full h-full object-cover" src="{{ $media->thumbnail_url }}">
                            @else
                                <div class="w-full h-full bg-surface-container-highest flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[16px] text-{{ $media->type === 'reel' ? 'primary' : 'tertiary' }}">
                                        {{ $media->type === 'reel' ? 'play_circle' : ($media->type === 'virtual_tour' ? 'floorplan' : 'photo') }}
                                    </span>
                                </div>
                            @endif
                            <span class="absolute bottom-0.5 left-1 text-[8px] font-{{ $index === 0 ? 'bold' : 'medium' }} bg-background/80 px-1 rounded text-{{ $index === 0 ? 'primary' : 'on-surface' }} leading-tight">
                                {{ $media->caption ?? $media->type }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Lower Property Context & Actions --}}
            <div class="flex-1 px-4 py-3 flex flex-col justify-between bg-surface-container-lowest">
                {{-- Top Detail Group --}}
                <div class="flex flex-col gap-1.5">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex flex-col min-w-0">
                            @if($property->auction)
                                <div class="flex items-center gap-1.5">
                                    <span class="text-[9px] font-bold px-1.5 py-0.2 rounded bg-surface-container-highest text-tertiary">COURT VERIFIED</span>
                                </div>
                            @endif
                            <h2 class="text-base font-bold text-on-surface truncate tracking-tight">{{ $property->title }}</h2>
                            <p class="text-xs text-on-surface-variant truncate">
                                {{ $property->bedrooms }} BHK {{ $property->property_type }} • {{ number_format($property->carpet_area_sqft ?? $property->area) }} sq.ft • {{ $property->locality }}, {{ $property->city }}
                            </p>
                        </div>
                        <div class="flex flex-col items-end flex-shrink-0">
                            @if($property->auction && $property->auction->market_value)
                                <div class="flex items-baseline gap-1">
                                    <span class="text-xs text-on-surface-variant line-through">₹{{ $property->auction->formatted_market_value }}</span>
                                    <span class="text-lg font-bold text-tertiary leading-none">₹{{ $property->formatted_price }}</span>
                                </div>
                                <span class="text-[10px] text-tertiary font-medium mt-0.5">Reserve Bid Starting</span>
                            @else
                                <span class="text-lg font-bold text-primary leading-none">₹{{ $property->formatted_price }}</span>
                                @if($property->price_per_sqft)
                                    <span class="text-[10px] text-on-surface-variant mt-0.5">₹{{ number_format($property->price_per_sqft) }} / sq.ft</span>
                                @endif
                            @endif
                        </div>
                    </div>

                    {{-- Compact Icon Spec Chips --}}
                    <div class="flex items-center justify-between gap-2 pt-0.5">
                        <div class="flex items-center gap-2 text-on-surface-variant text-[11px]">
                            <span class="flex items-center gap-0.5 px-1.5 py-0.5 rounded bg-surface-container text-on-surface">
                                <span class="material-symbols-outlined text-[13px] text-primary">bed</span> {{ $property->bedrooms }} BHK
                            </span>
                            @if($property->parking_count)
                                <span class="flex items-center gap-0.5 px-1.5 py-0.5 rounded bg-surface-container text-on-surface">
                                    <span class="material-symbols-outlined text-[13px] text-tertiary">directions_car</span> {{ $property->parking_count }} Prk
                                </span>
                            @endif
                            @if($property->facing)
                                <span class="flex items-center gap-0.5 px-1.5 py-0.5 rounded bg-surface-container text-on-surface">
                                    <span class="material-symbols-outlined text-[13px] text-primary">balcony</span> {{ $property->facing }}
                                </span>
                            @endif
                        </div>
                        @if($property->ai_insight)
                            <div class="flex items-center gap-1 px-2 py-0.5 rounded-full bg-tertiary-container/40 border border-tertiary/30 text-tertiary-fixed text-[10px] font-bold">
                                <span class="material-symbols-outlined text-[12px] text-tertiary">trending_up</span>
                                <span>{{ $property->ai_insight }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Direct Action CTA Buttons --}}
                <div class="grid grid-cols-2 gap-2 my-1">
                    @if($property->auction)
                        <button wire:click="downloadBidNotice({{ $property->id }})"
                                class="flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-surface-container-highest text-on-surface text-xs font-bold active:scale-95 transition-transform">
                            <span class="material-symbols-outlined text-[16px]">download</span>
                            <span>Bid Notice PDF</span>
                        </button>
                        <button wire:click="registerBid({{ $property->id }})"
                                class="flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-tertiary text-on-tertiary text-xs font-bold active:scale-95 transition-transform shadow-md">
                            <span class="material-symbols-outlined text-[16px]">how_to_reg</span>
                            <span>Register Bid</span>
                        </button>
                    @else
                        <a href="https://wa.me/?text={{ urlencode('Hi, I am interested in ' . $property->title) }}" target="_blank"
                           class="flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-tertiary text-on-tertiary text-xs font-bold active:scale-95 transition-transform">
                            <span class="material-symbols-outlined text-[16px]">chat</span>
                            <span>WhatsApp Seller</span>
                        </a>
                        <button wire:click="scheduleVisit({{ $property->id }})"
                                class="flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-primary text-on-primary text-xs font-bold active:scale-95 transition-transform shadow-md">
                            <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                            <span>Schedule Visit</span>
                        </button>
                    @endif
                </div>

                {{-- Inquiry Input Strip --}}
                <div class="flex items-center gap-2 pt-1 border-t border-outline-variant/30">
                    <div class="w-6 h-6 rounded-full bg-primary/20 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-[14px] text-primary">person</span>
                    </div>
                    <input wire:model="inquiryText" wire:keydown.enter="sendInquiry({{ $property->id }})"
                           class="flex-1 bg-surface-container text-on-surface text-[11px] px-3 py-1.5 rounded-full outline-none placeholder:text-on-surface-variant/80"
                           placeholder="Add inquiry or ask ReelEstate AI..." type="text">
                    <button wire:click="sendInquiry({{ $property->id }})" aria-label="Send Inquiry"
                            class="w-6 h-6 rounded-full bg-primary text-on-primary flex items-center justify-center active:scale-90 transition-transform">
                        <span class="material-symbols-outlined text-[13px]">send</span>
                    </button>
                </div>
            </div>

            {{-- Scroll Down Hint --}}
            @if($loop->first)
                <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 z-20 pointer-events-none flex flex-col items-center opacity-70 animate-bounce">
                    <span class="material-symbols-outlined text-[18px] text-primary">keyboard_arrow_down</span>
                </div>
            @endif
        </article>
    @endforeach
</div>
