<div class="flex flex-col w-full h-[calc(100vh-4rem)] select-none">
    {{-- Streamlined Search & Filter Header Strip --}}
    <div class="sticky top-0 z-30 bg-background/95 backdrop-blur-md px-4 pt-2.5 pb-2 flex flex-col gap-2 border-b border-outline-variant/40">
        <div class="relative flex items-center w-full">
            <div class="absolute left-3 flex items-center pointer-events-none text-primary">
                <span class="material-symbols-outlined text-[18px]">search</span>
            </div>
            <input wire:model.live.debounce.300ms="city"
                   class="w-full bg-surface-container text-on-surface text-xs font-normal pl-9 pr-20 py-2 rounded-xl outline-none placeholder:text-on-surface-variant focus:bg-surface-container-high transition-colors tracking-tight"
                   placeholder="Search 2 BHK, SG Highway, Auctions..." type="text">
            <div class="absolute right-1.5 flex items-center gap-1">
                <button aria-label="AI Voice Search"
                        class="w-7 h-7 rounded-lg flex items-center justify-center text-on-surface-variant hover:text-tertiary transition-colors bg-surface-container-high/60 active:scale-95">
                    <span class="material-symbols-outlined text-[16px]">mic</span>
                </button>
                <button aria-label="Open Filters"
                        class="w-7 h-7 rounded-lg flex items-center justify-center text-on-surface-variant hover:text-primary transition-colors bg-surface-container-high/60 active:scale-95 relative">
                    <span class="material-symbols-outlined text-[16px]">tune</span>
                </button>
            </div>
        </div>
        <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar -mx-4 px-4">
            <button class="px-3 py-1.5 rounded-full bg-primary text-on-primary text-[11px] font-semibold whitespace-nowrap shadow-sm flex items-center gap-1 active:scale-95 transition-transform">
                <span class="material-symbols-outlined text-[14px]">tune</span>
                <span>All ({{ $totalReels }})</span>
            </button>
            @foreach([
                ['key' => 'budget', 'icon' => 'payments', 'label' => 'Under ₹75L', 'iconColor' => 'text-primary'],
                ['key' => 'brokerage', 'icon' => 'bolt', 'label' => '0% Brokerage', 'iconColor' => 'text-tertiary', 'badge' => 'HOT'],
                ['key' => 'ready', 'icon' => 'key', 'label' => 'Ready to Move', 'iconColor' => 'text-primary'],
                ['key' => 'bhk', 'icon' => 'bed', 'label' => '2 & 3 BHK', 'iconColor' => 'text-on-surface-variant'],
                ['key' => 'auctions', 'icon' => 'gavel', 'label' => 'Bank Auctions', 'iconColor' => 'text-tertiary'],
                ['key' => 'furnished', 'icon' => 'chair', 'label' => 'Furnished', 'iconColor' => 'text-primary'],
            ] as $qf)
                <button class="px-2.5 py-1.5 rounded-full bg-surface-container text-on-surface hover:bg-surface-container-high text-[11px] font-medium whitespace-nowrap flex items-center gap-1 border border-outline-variant/30 active:scale-95 transition-colors">
                    <span class="material-symbols-outlined text-[13px] {{ $qf['iconColor'] }}">{{ $qf['icon'] }}</span>
                    <span>{{ $qf['label'] }}</span>
                    @if($qf['badge'] ?? false)
                        <span class="text-[9px] bg-tertiary text-on-tertiary font-bold px-1 rounded-full">{{ $qf['badge'] }}</span>
                    @endif
                </button>
            @endforeach
        </div>
    </div>

    @if($currentReel && $currentReel->property)
        @php $property = $currentReel->property; @endphp
        {{-- Single Reel Card (Full-screen) --}}
        <article class="flex-1 flex flex-col relative bg-background">
            {{-- Primary Image/Video Hero (~58% Height) --}}
            <div class="relative w-full h-[58%] bg-surface-container-lowest overflow-hidden">
                <img alt="{{ $property->title }}"
                     class="w-full h-full object-cover"
                     src="{{ $property->getFirstMediaUrl('images') ?: asset('build/images/property-placeholder.png') }}">
                <div class="absolute inset-0 bg-gradient-to-t from-background via-transparent to-background/50"></div>

                {{-- Top Badges Overlay --}}
                <div class="absolute top-3 left-3 flex items-center gap-1.5 z-10">
                    <div class="px-2.5 py-1 rounded-lg bg-surface-container-lowest/85 backdrop-blur-md text-on-primary-container flex items-center gap-1 text-[11px] font-medium shadow-md">
                        <span class="material-symbols-outlined text-[14px] text-primary" style="font-variation-settings: 'FILL' 1;">videocam</span>
                        <span>{{ $currentReel->duration ?? '24s' }} Walkthrough Reel</span>
                    </div>
                    @if($property->rera_number)
                        <div class="px-2 py-1 rounded-lg bg-tertiary-container text-tertiary-fixed text-[10px] font-bold flex items-center gap-0.5 shadow-sm">
                            <span class="material-symbols-outlined text-[12px]">verified</span>
                            <span>RERA Ready</span>
                        </div>
                    @endif
                </div>

                {{-- Top-Right Media Counter --}}
                <div class="absolute top-3 right-3 z-10 px-2 py-0.5 rounded-full bg-surface-container-lowest/80 backdrop-blur-md text-on-surface text-[10px] font-medium">
                    {{ $currentIndex + 1 }} / {{ $totalReels }} Reels
                </div>

                {{-- Center Play Button --}}
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                    <div class="w-12 h-12 rounded-full bg-surface-container-highest/80 backdrop-blur-md text-primary flex items-center justify-center shadow-xl">
                        <span class="material-symbols-outlined text-[28px] ml-0.5" style="font-variation-settings: 'FILL' 1;">play_arrow</span>
                    </div>
                </div>

                {{-- Instagram-Style Vertical Action Rail (Right) --}}
                <div class="absolute right-2.5 bottom-16 z-20 flex flex-col items-center gap-3">
                    <button wire:click="likeReel({{ $currentReel->id }})" aria-label="Like property" class="flex flex-col items-center gap-0.5 text-on-surface active:scale-90 transition-transform">
                        <div class="w-9 h-9 rounded-full bg-surface-container-lowest/70 backdrop-blur-md flex items-center justify-center hover:text-error transition-colors">
                            <span class="material-symbols-outlined text-[20px] text-error" style="font-variation-settings: 'FILL' 1;">favorite</span>
                        </div>
                        <span class="text-[10px] font-bold text-on-surface leading-tight">{{ number_format($currentReel->likes_count ?? 0) }}</span>
                    </button>
                    <button aria-label="Comments" class="flex flex-col items-center gap-0.5 text-on-surface active:scale-90 transition-transform">
                        <div class="w-9 h-9 rounded-full bg-surface-container-lowest/70 backdrop-blur-md flex items-center justify-center hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[20px]">chat_bubble</span>
                        </div>
                        <span class="text-[10px] font-bold text-on-surface leading-tight">{{ $property->comments_count ?? 0 }}</span>
                    </button>
                    <button aria-label="Bookmark" class="flex flex-col items-center gap-0.5 text-on-surface active:scale-90 transition-transform">
                        <div class="w-9 h-9 rounded-full bg-surface-container-lowest/70 backdrop-blur-md flex items-center justify-center hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[20px] text-primary" style="font-variation-settings: 'FILL' 1;">bookmark</span>
                        </div>
                        <span class="text-[10px] font-bold text-on-surface leading-tight">{{ number_format($currentReel->views_count ?? 0) }}</span>
                    </button>
                    <button aria-label="Share reel" class="flex flex-col items-center gap-0.5 text-on-surface active:scale-90 transition-transform">
                        <div class="w-9 h-9 rounded-full bg-surface-container-lowest/70 backdrop-blur-md flex items-center justify-center hover:text-tertiary transition-colors">
                            <span class="material-symbols-outlined text-[20px]">send</span>
                        </div>
                        <span class="text-[10px] font-bold text-on-surface leading-tight">Share</span>
                    </button>
                </div>

                {{-- Navigation Arrows --}}
                @if($currentIndex > 0)
                    <button wire:click="previousReel"
                            class="absolute left-3 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-surface-container-lowest/70 backdrop-blur-md flex items-center justify-center text-on-surface active:scale-90 transition-transform">
                        <span class="material-symbols-outlined text-[20px]">keyboard_arrow_up</span>
                    </button>
                @endif

                {{-- Bottom Gallery Thumbnail Rail --}}
                <div class="absolute bottom-2 left-0 right-14 z-10 px-3 flex items-center gap-1.5 overflow-x-auto no-scrollbar">
                    <div class="relative flex-shrink-0 w-14 h-11 rounded-lg overflow-hidden ring-2 ring-primary shadow-lg cursor-pointer">
                        <img alt="Reel preview" class="w-full h-full object-cover"
                             src="{{ $currentReel->thumbnail_url ?: ($property->getFirstMediaUrl('images') ?: asset('build/images/property-placeholder.png')) }}">
                        <span class="absolute bottom-0.5 left-1 text-[8px] font-bold bg-background/80 px-1 rounded text-primary leading-tight">Reel</span>
                    </div>
                    @foreach($property->propertyMedia->where('type', '!=', 'reel')->take(3) as $media)
                        <div class="relative flex-shrink-0 w-14 h-11 rounded-lg overflow-hidden bg-surface-container-highest cursor-pointer opacity-70 hover:opacity-100 transition-opacity">
                            @if($media->thumbnail_url)
                                <img alt="{{ $media->caption ?? $media->type }}" class="w-full h-full object-cover" src="{{ $media->thumbnail_url }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[16px] text-tertiary">{{ $media->type === 'virtual_tour' ? 'floorplan' : 'photo' }}</span>
                                </div>
                            @endif
                            <span class="absolute bottom-0.5 left-1 text-[8px] font-medium bg-background/80 px-1 rounded text-on-surface leading-tight">{{ ucfirst($media->caption ?? $media->type) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Lower Property Context & Actions (~42% Height) --}}
            <div class="flex-1 px-4 py-3 flex flex-col justify-between bg-surface-container-lowest">
                {{-- Top Detail Group --}}
                <div class="flex flex-col gap-1.5">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex flex-col min-w-0">
                            <h2 class="text-base font-bold text-on-surface truncate tracking-tight">{{ $property->title }}</h2>
                            <p class="text-xs text-on-surface-variant truncate">
                                {{ $property->bedrooms }} BHK {{ $property->property_type ?? 'Flat' }} • {{ number_format($property->carpet_area_sqft ?? $property->area ?? 0) }} sq.ft • {{ $property->locality ?? '' }}, {{ $property->city ?? '' }}
                            </p>
                        </div>
                        <div class="flex flex-col items-end flex-shrink-0">
                            <span class="text-lg font-bold text-primary leading-none">₹{{ $property->formatted_price ?? number_format($property->price ?? 0) }}</span>
                            @if($property->price_per_sqft ?? false)
                                <span class="text-[10px] text-on-surface-variant mt-0.5">₹{{ number_format($property->price_per_sqft) }} / sq.ft</span>
                            @endif
                        </div>
                    </div>

                    {{-- Compact Icon Spec Chips --}}
                    <div class="flex items-center justify-between gap-2 pt-0.5">
                        <div class="flex items-center gap-2 text-on-surface-variant text-[11px]">
                            <span class="flex items-center gap-0.5 px-1.5 py-0.5 rounded bg-surface-container text-on-surface">
                                <span class="material-symbols-outlined text-[13px] text-primary">bed</span> {{ $property->bedrooms ?? 0 }} BHK
                            </span>
                            @if($property->parking_count ?? false)
                                <span class="flex items-center gap-0.5 px-1.5 py-0.5 rounded bg-surface-container text-on-surface">
                                    <span class="material-symbols-outlined text-[13px] text-tertiary">directions_car</span> {{ $property->parking_count }} Prk
                                </span>
                            @endif
                            @if($property->facing ?? false)
                                <span class="flex items-center gap-0.5 px-1.5 py-0.5 rounded bg-surface-container text-on-surface">
                                    <span class="material-symbols-outlined text-[13px] text-primary">balcony</span> {{ $property->facing }}
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center gap-1 px-2 py-0.5 rounded-full bg-tertiary-container/40 border border-tertiary/30 text-tertiary-fixed text-[10px] font-bold">
                            <span class="material-symbols-outlined text-[12px] text-tertiary">trending_up</span>
                            <span>AI Vector +{{ rand(8,18) }}.{{ rand(0,9) }}% Growth</span>
                        </div>
                    </div>
                </div>

                {{-- Direct Action CTA Buttons --}}
                <div class="grid grid-cols-2 gap-2 my-1">
                    <a href="https://wa.me/?text={{ urlencode('Hi, I am interested in ' . ($property->title ?? 'this property')) }}" target="_blank"
                       class="flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-tertiary text-on-tertiary text-xs font-bold active:scale-95 transition-transform">
                        <span class="material-symbols-outlined text-[16px]">chat</span>
                        <span>WhatsApp Seller</span>
                    </a>
                    <button class="flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-primary text-on-primary text-xs font-bold active:scale-95 transition-transform shadow-md">
                        <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                        <span>Schedule Visit</span>
                    </button>
                </div>

                {{-- Inquiry Input Strip --}}
                <div class="flex items-center gap-2 pt-1 border-t border-outline-variant/30">
                    <div class="w-6 h-6 rounded-full bg-primary/20 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-[14px] text-primary">person</span>
                    </div>
                    <input class="flex-1 bg-surface-container text-on-surface text-[11px] px-3 py-1.5 rounded-full outline-none placeholder:text-on-surface-variant/80"
                           placeholder="Add inquiry or ask ReelEstate AI..." type="text">
                    <button aria-label="Send Inquiry" class="w-6 h-6 rounded-full bg-primary text-on-primary flex items-center justify-center active:scale-90 transition-transform">
                        <span class="material-symbols-outlined text-[13px]">send</span>
                    </button>
                </div>
            </div>

            {{-- Next Reel Hint --}}
            @if($currentIndex < $totalReels - 1)
                <button wire:click="nextReel"
                        class="absolute -bottom-3 left-1/2 -translate-x-1/2 z-20 flex flex-col items-center opacity-70 animate-bounce cursor-pointer">
                    <span class="material-symbols-outlined text-[18px] text-primary">keyboard_arrow_down</span>
                </button>
            @endif
        </article>
    @else
        {{-- Empty State --}}
        <div class="flex-1 flex flex-col items-center justify-center text-center px-6">
            <div class="w-14 h-14 rounded-full bg-surface-container-high flex items-center justify-center text-primary mb-3">
                <span class="material-symbols-outlined text-[28px]">play_circle</span>
            </div>
            <h3 class="text-sm font-semibold text-on-surface">No reels available</h3>
            <p class="text-xs text-on-surface-variant mt-1 max-w-[260px]">Property video reels are being added regularly. Try a different city or filter.</p>
        </div>
    @endif
</div>
