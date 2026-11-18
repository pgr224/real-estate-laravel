<div
    x-data="{
        activeMediaIndex: 0,
        activeTab: 'cinema',
        showEmiCalc: false,
        isPlaying: false,
        commentSort: 'top',
    }"
    class="flex flex-col w-full text-on-surface"
>
    {{-- Sub-Navigation & Breadcrumbs Ribbon --}}
    <section class="w-full bg-surface-container-low px-4 lg:px-8 py-3.5 shadow-sm">
        <div class="max-w-[1720px] mx-auto flex flex-col xl:flex-row items-start xl:items-center justify-between gap-3 text-xs">
            {{-- Breadcrumbs & Quick Tags --}}
            <div class="flex flex-wrap items-center gap-2 text-on-surface-variant font-mono">
                <a href="{{ route('home') }}" wire:navigate class="hover:text-primary transition-colors cursor-pointer">India</a>
                <span class="text-secondary select-none">/</span>
                <a href="{{ route('marketplace', ['city' => $property->city ?? '']) }}" wire:navigate class="hover:text-primary transition-colors cursor-pointer">{{ $property->city ?? 'City' }}</a>
                <span class="text-secondary select-none">/</span>
                <a href="{{ route('marketplace') }}" wire:navigate class="hover:text-primary transition-colors cursor-pointer">{{ $property->locality ?? $property->location ?? 'Locality' }}</a>
                <span class="text-secondary select-none">/</span>
                <span class="text-on-surface font-semibold font-headline">{{ $property->title }}</span>
                <span class="text-secondary select-none">/</span>
                <span class="bg-surface-variant px-2 py-0.5 rounded text-[11px] font-medium text-primary">#{{ $property->listing_id ?? 'RE-' . $property->id }}</span>
                @if($property->rera_number)
                    <span class="bg-tertiary-container/40 text-tertiary px-2 py-0.5 rounded text-[11px] font-medium flex items-center gap-1">
                        <span class="material-symbols-outlined text-[12px]" style="font-variation-settings: 'FILL' 1;">verified</span>
                        RERA Approved
                    </span>
                @endif
            </div>
            {{-- Feed Ribbon Controls & View Toggles --}}
            <div class="flex flex-wrap items-center gap-2.5">
                <div class="flex items-center gap-1.5 bg-surface-container px-2.5 py-1.5 rounded-lg text-on-surface-variant">
                    <span class="material-symbols-outlined text-sm text-primary">tune</span>
                    <span class="text-on-surface font-medium">{{ $property->city }} {{ $property->area ?? 'West' }}</span>
                    <span class="text-secondary">•</span>
                    <span>₹{{ number_format($property->price / 100000) }}L</span>
                    <span class="text-secondary">•</span>
                    <span class="text-on-surface font-medium">{{ $property->bhk ?? 2 }} BHK</span>
                </div>
                <div class="flex items-center bg-surface-container-high rounded-lg p-0.5 shadow-sm">
                    <button
                        @click="activeTab = 'cinema'"
                        :class="activeTab === 'cinema' ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:text-on-surface'"
                        class="flex items-center gap-1 px-2.5 py-1 rounded font-medium transition-all"
                    >
                        <span class="material-symbols-outlined text-sm">play_circle</span>
                        <span>Cinema Reel</span>
                    </button>
                    <button
                        @click="activeTab = 'map'"
                        :class="activeTab === 'map' ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:text-on-surface'"
                        class="flex items-center gap-1 px-2.5 py-1 rounded transition-colors"
                    >
                        <span class="material-symbols-outlined text-sm">map</span>
                        <span>Map & Drone</span>
                    </button>
                    <button
                        @click="activeTab = 'streetview'"
                        :class="activeTab === 'streetview' ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:text-on-surface'"
                        class="flex items-center gap-1 px-2.5 py-1 rounded transition-colors"
                    >
                        <span class="material-symbols-outlined text-sm">360</span>
                        <span>Street View</span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    {{-- Main Showcase Container --}}
    <div class="w-full max-w-[1720px] mx-auto px-4 lg:px-8 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- LEFT COLUMN: Cinema Reel Stage, Multi-Media Strip & Community Inquiries --}}
            <section class="lg:col-span-7 flex flex-col gap-6">

                {{-- Cinema Stage --}}
                <div class="relative w-full rounded-xl overflow-hidden bg-surface-container-lowest shadow-xl aspect-[16/10] sm:aspect-[16/9] group select-none">
                    {{-- Main Video Canvas --}}
                    <div
                        class="w-full h-full bg-cover bg-center transition-transform duration-700 ease-out group-hover:scale-[1.01]"
                        style="background-image: url('{{ $property->getFirstMediaUrl('images') ?: asset('build/images/property-placeholder.png') }}');"
                    ></div>
                    {{-- Film Scrim Overlay --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-background via-background/20 to-background/50 pointer-events-none"></div>

                    {{-- Top Stage Badges --}}
                    <div class="absolute top-4 left-4 right-4 flex items-center justify-between pointer-events-none">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-1 rounded bg-surface/85 backdrop-blur-md text-xs font-semibold text-on-surface flex items-center gap-1.5 shadow-sm">
                                <span class="w-2 h-2 rounded-full bg-error animate-pulse"></span>
                                <span>{{ $property->reel_duration ?? '0:24' }} Walkthrough Reel</span>
                            </span>
                            @if($property->rera_number)
                                <span class="px-2 py-1 rounded bg-surface/85 backdrop-blur-md text-xs text-tertiary font-mono font-medium flex items-center gap-1">
                                    <span class="material-symbols-outlined text-xs">verified</span>
                                    {{ $property->rera_number }}
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded bg-surface/80 backdrop-blur-md text-[11px] font-mono text-on-surface-variant">
                                <span x-text="activeMediaIndex + 1">1</span> / {{ $property->media->count() ?: 6 }} Media
                            </span>
                        </div>
                    </div>

                    {{-- Desktop Floating Social Bar (Right Overlay) --}}
                    <div class="absolute right-4 bottom-24 flex flex-col items-center gap-3.5 z-10">
                        <button wire:click="toggleLike" class="group/btn flex flex-col items-center gap-1">
                            <div class="w-10 h-10 rounded-full bg-surface-container/90 backdrop-blur-md flex items-center justify-center text-on-surface group-hover/btn:bg-primary group-hover/btn:text-on-primary transition-all shadow-md">
                                <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">favorite</span>
                            </div>
                            <span class="text-[11px] font-mono font-semibold text-on-surface drop-shadow">{{ $likesFormatted ?? '18.4k' }}</span>
                        </button>
                        <button wire:click="$dispatch('open-comments')" class="group/btn flex flex-col items-center gap-1">
                            <div class="w-10 h-10 rounded-full bg-surface-container/90 backdrop-blur-md flex items-center justify-center text-on-surface group-hover/btn:bg-primary group-hover/btn:text-on-primary transition-all shadow-md">
                                <span class="material-symbols-outlined text-lg">chat_bubble</span>
                            </div>
                            <span class="text-[11px] font-mono font-semibold text-on-surface drop-shadow">{{ $commentsCount ?? 412 }}</span>
                        </button>
                        <button wire:click="toggleBookmark" class="group/btn flex flex-col items-center gap-1">
                            <div class="w-10 h-10 rounded-full bg-surface-container/90 backdrop-blur-md flex items-center justify-center text-on-surface group-hover/btn:bg-primary group-hover/btn:text-on-primary transition-all shadow-md">
                                <span class="material-symbols-outlined text-lg">bookmark</span>
                            </div>
                            <span class="text-[11px] font-mono font-semibold text-on-surface drop-shadow">{{ $bookmarksFormatted ?? '2.1k' }}</span>
                        </button>
                        <button wire:click="shareProperty" class="group/btn flex flex-col items-center gap-1">
                            <div class="w-10 h-10 rounded-full bg-surface-container/90 backdrop-blur-md flex items-center justify-center text-tertiary hover:bg-tertiary hover:text-on-tertiary transition-all shadow-md">
                                <span class="material-symbols-outlined text-lg">share</span>
                            </div>
                            <span class="text-[11px] font-mono font-semibold text-on-surface drop-shadow">Share</span>
                        </button>
                    </div>

                    {{-- Video Player Controls Bottom Scrim Bar --}}
                    <div class="absolute bottom-0 inset-x-0 p-4 bg-gradient-to-t from-background via-background/80 to-transparent flex flex-col gap-2.5 z-10">
                        <div class="relative w-full h-1.5 bg-surface-variant rounded-full cursor-pointer overflow-hidden group/scrub">
                            <div class="absolute left-0 top-0 bottom-0 bg-primary w-[58%] rounded-full relative">
                                <span class="absolute right-0 top-1/2 -translate-y-1/2 w-3 h-3 rounded-full bg-on-surface shadow-sm opacity-0 group-hover/scrub:opacity-100 transition-opacity"></span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-3">
                                <button
                                    @click="isPlaying = !isPlaying"
                                    class="w-8 h-8 rounded-lg bg-primary text-on-primary flex items-center justify-center shadow-md hover:bg-primary-fixed-dim transition-colors"
                                >
                                    <span class="material-symbols-outlined text-base" style="font-variation-settings: 'FILL' 1;" x-text="isPlaying ? 'pause' : 'play_arrow'">pause</span>
                                </button>
                                <button class="text-on-surface hover:text-primary transition-colors flex items-center">
                                    <span class="material-symbols-outlined text-lg">volume_up</span>
                                </button>
                                <span class="font-mono text-on-surface-variant text-[11px]">
                                    <span class="text-on-surface font-semibold">0:14</span> / {{ $property->reel_duration ?? '0:24' }}
                                </span>
                            </div>
                            <div class="flex items-center gap-3 text-on-surface-variant">
                                <button class="hover:text-on-surface transition-colors flex items-center gap-1 text-[11px]">
                                    <span class="material-symbols-outlined text-sm">speed</span>
                                    <span>1.0x</span>
                                </button>
                                <button class="hover:text-on-surface transition-colors">
                                    <span class="material-symbols-outlined text-sm">subtitles</span>
                                </button>
                                <button class="hover:text-on-surface transition-colors">
                                    <span class="material-symbols-outlined text-sm">fullscreen</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Media Switcher Strip (Thumbnails) --}}
                <div class="flex flex-col gap-2">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-headline font-semibold text-on-surface uppercase tracking-wider text-[11px]">Property Media Catalog ({{ $property->media->count() ?: 6 }} Views)</span>
                        <span class="text-on-surface-variant font-mono text-[11px]">Click to inspect perspective</span>
                    </div>
                    <div class="grid grid-cols-3 sm:grid-cols-6 gap-2.5">
                        @foreach($property->media as $index => $mediaItem)
                            <div
                                @click="activeMediaIndex = {{ $index }}"
                                :class="activeMediaIndex === {{ $index }} ? 'ring-2 ring-primary' : ''"
                                class="group relative rounded-lg overflow-hidden aspect-[4/3] bg-surface-container cursor-pointer shadow-sm hover:brightness-110 transition-all"
                            >
                                @if($mediaItem->type === 'video' || $mediaItem->type === 'reel')
                                    <div
                                        class="w-full h-full bg-cover bg-center brightness-90 group-hover:scale-105 transition-transform"
                                        style="background-image: url('{{ $mediaItem->thumbnail_url ?: $mediaItem->getUrl() }}');"
                                    ></div>
                                    <div class="absolute inset-0 bg-background/40 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-primary text-xl" style="font-variation-settings: 'FILL' 1;">play_circle</span>
                                    </div>
                                @elseif($mediaItem->type === 'floor_plan')
                                    <div class="w-full h-full bg-surface-container-high flex flex-col items-center justify-center p-2 text-on-surface-variant group-hover:text-primary transition-colors">
                                        <span class="material-symbols-outlined text-xl">architecture</span>
                                        <span class="text-[9px] font-mono mt-0.5">{{ number_format($property->carpet_area_sqft ?? $property->super_buildup_area_sqft) }} sq.ft</span>
                                    </div>
                                @elseif($mediaItem->type === 'streetview')
                                    <div class="w-full h-full bg-surface-container-high flex flex-col items-center justify-center p-2 text-on-surface-variant group-hover:text-primary transition-colors">
                                        <span class="material-symbols-outlined text-xl">streetview</span>
                                        <span class="text-[9px] font-mono mt-0.5">360° Cam</span>
                                    </div>
                                @else
                                    <div
                                        class="w-full h-full bg-cover bg-center group-hover:scale-105 transition-transform"
                                        style="background-image: url('{{ $mediaItem->getUrl() ?: asset('build/images/property-placeholder.png') }}');"
                                    ></div>
                                    @if($mediaItem->type === 'drone')
                                        <div class="absolute top-1 right-1 px-1 py-0.2 rounded bg-surface/80 text-[8px] font-bold text-tertiary">3D DRONE</div>
                                    @endif
                                @endif
                                <span class="absolute bottom-1 left-1 right-1 text-[9px] font-semibold text-center truncate bg-surface/90 px-1 py-0.5 rounded text-on-surface">{{ $mediaItem->caption ?? ucfirst($mediaItem->type) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Comments & Inquiries Feed --}}
                <div class="w-full rounded-xl bg-surface-container p-5 flex flex-col gap-4 shadow-md">
                    <div class="flex items-center justify-between pb-3 bg-surface-container-high/40 -mx-5 -mt-5 px-5 pt-4 rounded-t-xl">
                        <div class="flex items-center gap-2">
                            <span class="font-headline font-semibold text-sm text-on-surface">Comments</span>
                            <span class="px-2 py-0.5 rounded-full bg-surface-variant text-[11px] font-mono text-primary">{{ $commentsCount ?? 0 }} comments</span>
                        </div>
                        <div class="flex items-center gap-1 text-xs text-on-surface-variant">
                            <span>Sort:</span>
                            <button
                                @click="commentSort = commentSort === 'top' ? 'recent' : 'top'"
                                class="font-medium text-on-surface flex items-center hover:text-primary"
                            >
                                <span x-text="commentSort === 'top' ? 'Top Inquiries' : 'Most Recent'">Top Inquiries</span>
                                <span class="material-symbols-outlined text-xs">expand_more</span>
                            </button>
                        </div>
                    </div>

                    {{-- AI Copilot Instant Analysis Card --}}
                    @if($aiAnalysis)
                        <div class="p-3.5 rounded-lg bg-surface-container-low flex items-start gap-3 shadow-inner">
                            <div class="w-7 h-7 rounded-md bg-primary-container text-on-primary-container flex items-center justify-center shrink-0 mt-0.5">
                                <span class="material-symbols-outlined text-base">smart_toy</span>
                            </div>
                            <div class="flex-1 text-xs space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold text-primary">ReelEstate AI Copilot</span>
                                    <span class="px-1.5 py-0.2 rounded bg-tertiary-container/30 text-tertiary text-[10px] font-mono font-medium">Title Verified</span>
                                    <span class="text-secondary text-[11px]">Just now</span>
                                </div>
                                <p class="text-on-surface-variant leading-relaxed">{{ is_array($aiAnalysis) ? ($aiAnalysis['recommendation'] ?? 'High investment potential with strong rental yield.') : $aiAnalysis }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Recent Inquiries List --}}
                    <div class="flex flex-col gap-3.5">
                        @foreach($comments as $comment)
                            <div wire:key="comment-{{ $comment->id }}" class="flex items-start gap-3 text-xs">
                                <img
                                    class="w-7 h-7 rounded-full object-cover shrink-0 mt-0.5"
                                    src="{{ $comment->user->avatar_url ?: asset('build/images/avatar-placeholder.png') }}"
                                    alt="{{ $comment->user->name }}"
                                />
                                <div class="flex-1 space-y-1.5">
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-on-surface">{{ $comment->user->name }}</span>
                                        @if($comment->user->profession)
                                            <span class="px-1.5 py-0.2 rounded bg-surface-variant text-on-surface-variant text-[10px]">{{ $comment->user->profession }} • {{ $comment->user->locality }}</span>
                                        @endif
                                        <span class="text-secondary text-[10px] font-mono">{{ $comment->created_at->diffForHumans(short: true) }}</span>
                                    </div>
                                    <p class="text-on-surface-variant">{{ $comment->body }}</p>

                                    {{-- Nested Reply --}}
                                    @foreach($comment->replies as $reply)
                                        <div class="p-2.5 rounded-lg bg-surface-container-high/60 flex items-start gap-2.5 mt-2">
                                            <div class="w-5 h-5 rounded-full bg-primary flex items-center justify-center text-on-primary shrink-0 text-[10px] font-bold">
                                                {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                                            </div>
                                            <div class="space-y-0.5">
                                                <div class="flex items-center gap-1.5">
                                                    <span class="font-semibold text-primary text-[11px]">{{ $reply->user->name }}</span>
                                                    @if($reply->user->is_verified)
                                                        <span class="material-symbols-outlined text-tertiary text-[13px]" style="font-variation-settings: 'FILL' 1;">verified</span>
                                                    @endif
                                                    <span class="text-secondary text-[10px]">{{ $reply->created_at->diffForHumans(short: true) }}</span>
                                                </div>
                                                <p class="text-on-surface text-[11px] leading-relaxed">{{ $reply->body }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Quick Question Chips --}}
                    <div class="flex flex-wrap items-center gap-2 pt-1 text-[11px]">
                        <span class="text-secondary">Quick ask:</span>
                        @foreach($quickQuestions ?? ['Ask about monthly maintenance', 'Request floor plan PDF', 'Check loan eligibility'] as $question)
                            <button
                                wire:click="askQuestion('{{ $question }}')"
                                class="px-2.5 py-1 rounded bg-surface-container-high hover:bg-surface-variant text-on-surface transition-colors"
                            >
                                {{ $question }}
                            </button>
                        @endforeach
                    </div>

                    {{-- Comment Input Bar --}}
                    <form wire:submit.prevent="submitComment" class="relative flex items-center mt-1">
                        <input
                            wire:model="newComment"
                            class="w-full bg-surface-container-lowest text-on-surface text-xs rounded-lg pl-3.5 pr-24 py-2.5 focus:outline-none focus:ring-1 focus:ring-primary placeholder:text-secondary"
                            placeholder="Ask about this listing or comment..."
                            type="text"
                        />
                        <div class="absolute right-2 flex items-center gap-1.5">
                            <button type="button" class="text-on-surface-variant hover:text-on-surface p-1">
                                <span class="material-symbols-outlined text-base">sentiment_satisfied</span>
                            </button>
                            <button type="submit" class="px-3 py-1 bg-primary text-on-primary rounded text-xs font-semibold hover:bg-primary-fixed-dim transition-colors">
                                Send
                            </button>
                        </div>
                    </form>
                </div>
            </section>

            {{-- RIGHT COLUMN: Property Dossier & Conversion Panel --}}
            <section class="lg:col-span-5 flex flex-col gap-6">

                {{-- Identity & Key Economics Card --}}
                <div class="rounded-xl bg-surface-container p-6 shadow-md flex flex-col gap-5">
                    {{-- Header & Title --}}
                    <div class="flex flex-col gap-1.5">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-mono text-tertiary font-semibold tracking-wider uppercase">{{ $property->builder_name ?? 'Builder' }}</span>
                            <span class="px-2 py-0.5 rounded bg-surface-variant text-secondary text-[10px] font-mono">Listed {{ $property->created_at->diffForHumans() }}</span>
                        </div>
                        <h1 class="text-2xl font-headline font-bold text-on-surface tracking-tight">{{ $property->title }}</h1>
                        <p class="text-xs text-on-surface-variant leading-relaxed">
                            {{ $property->bhk ?? 2 }} BHK Premium Flat • {{ number_format($property->super_buildup_area_sqft) }} sq.ft Super Built-up • {{ $property->locality }}, {{ $property->city }}
                        </p>
                    </div>

                    {{-- Price & Valuation Hero --}}
                    <div class="p-4 rounded-lg bg-surface-container-low flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 shadow-inner">
                        <div class="flex flex-col">
                            <div class="flex items-baseline gap-2">
                                <span class="text-3xl font-headline font-black text-on-surface">₹{{ number_format($property->price / 100000, 1) }} L</span>
                                <span class="text-xs font-mono text-secondary">₹{{ number_format($property->price_per_sqft) }} / sq.ft</span>
                            </div>
                            <span class="text-xs text-on-surface-variant font-mono mt-0.5">
                                Est. EMI <span class="text-primary font-semibold">₹{{ number_format($property->estimated_emi ?? round($property->price * 0.0071)) }}/mo</span> @ {{ $property->loan_rate ?? '8.5' }}% p.a.
                            </span>
                        </div>
                        <button
                            @click="showEmiCalc = !showEmiCalc"
                            class="px-2.5 py-1.5 rounded bg-surface-variant text-[11px] font-medium text-on-surface hover:text-primary transition-colors flex items-center gap-1 self-start sm:self-auto"
                        >
                            <span class="material-symbols-outlined text-sm">calculate</span>
                            <span>EMI Calc</span>
                        </button>
                    </div>

                    {{-- Quick Spec Highlights --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 text-xs">
                        <div class="p-2.5 rounded bg-surface-container-high flex flex-col">
                            <span class="text-secondary text-[10px] uppercase font-mono">Configuration</span>
                            <span class="text-on-surface font-semibold mt-0.5">{{ $property->bhk ?? 2 }} BHK + {{ $property->bathrooms ?? 2 }} Bath</span>
                        </div>
                        <div class="p-2.5 rounded bg-surface-container-high flex flex-col">
                            <span class="text-secondary text-[10px] uppercase font-mono">Floor Level</span>
                            <span class="text-on-surface font-semibold mt-0.5">{{ $property->floor_number ?? 'N/A' }} of {{ $property->total_floors ?? 'N/A' }} Floors</span>
                        </div>
                        <div class="p-2.5 rounded bg-surface-container-high flex flex-col">
                            <span class="text-secondary text-[10px] uppercase font-mono">Facing</span>
                            <span class="text-on-surface font-semibold mt-0.5">{{ $property->facing ?? 'West' }} (Vastu)</span>
                        </div>
                        <div class="p-2.5 rounded bg-surface-container-high flex flex-col">
                            <span class="text-secondary text-[10px] uppercase font-mono">Parking</span>
                            <span class="text-on-surface font-semibold mt-0.5">{{ $property->parking_count ?? 1 }} Covered Stilt</span>
                        </div>
                        <div class="p-2.5 rounded bg-surface-container-high flex flex-col">
                            <span class="text-secondary text-[10px] uppercase font-mono">Balcony</span>
                            <span class="text-on-surface font-semibold mt-0.5">{{ $property->balcony_count ?? 1 }} Large Garden-View</span>
                        </div>
                        <div class="p-2.5 rounded bg-surface-container-high flex flex-col">
                            <span class="text-secondary text-[10px] uppercase font-mono">Carpet Area</span>
                            <span class="text-on-surface font-semibold mt-0.5">{{ number_format($property->carpet_area_sqft) }} sq.ft</span>
                        </div>
                    </div>

                    {{-- AI Vector Valuation Pill --}}
                    @if($property->projected_gain)
                        <div class="p-3 rounded-lg bg-surface-container-high/70 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-tertiary text-base">trending_up</span>
                                <div>
                                    <span class="font-semibold text-on-surface">+{{ $property->projected_gain }}% YoY Projected Capital Gain</span>
                                    <p class="text-[10px] text-on-surface-variant">{{ $property->growth_catalyst ?? 'Metro expansion catalyst' }}</p>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 rounded bg-tertiary-container/30 text-tertiary font-mono text-[11px] font-bold">
                                {{ $property->ai_score ?? '9.2' }} AI Score
                            </span>
                        </div>
                    @endif

                    {{-- Dual High Velocity Conversion CTA --}}
                    <div class="flex flex-col gap-2.5 pt-1">
                        <button
                            wire:click="whatsappInquiry"
                            class="w-full py-3 rounded-lg bg-tertiary text-on-tertiary hover:bg-tertiary-fixed-dim font-headline font-semibold text-sm flex items-center justify-center gap-2 shadow-lg transition-all"
                        >
                            <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">chat</span>
                            <span>Inquiry WhatsApp</span>
                            <span class="text-[11px] font-normal opacity-80">(Avg reply: {{ $property->avg_reply_time ?? '4 mins' }})</span>
                        </button>
                        <button
                            wire:click="scheduleVisit"
                            class="w-full py-3 rounded-lg bg-primary text-on-primary hover:bg-primary-fixed-dim font-headline font-semibold text-sm flex items-center justify-center gap-2 shadow-lg transition-all"
                        >
                            <span class="material-symbols-outlined text-lg">event_available</span>
                            <span>Schedule Visit</span>
                        </button>
                        <div class="grid grid-cols-3 gap-2 pt-1 text-[11px]">
                            <button
                                wire:click="downloadBrochure"
                                class="p-2 rounded bg-surface-container-high hover:bg-surface-variant text-on-surface font-medium flex items-center justify-center gap-1 transition-colors"
                            >
                                <span class="material-symbols-outlined text-sm text-primary">download</span>
                                <span>Brochure</span>
                            </button>
                            <button
                                wire:click="broadcastProperty"
                                class="p-2 rounded bg-surface-container-high hover:bg-surface-variant text-on-surface font-medium flex items-center justify-center gap-1 transition-colors"
                            >
                                <span class="material-symbols-outlined text-sm text-tertiary">share</span>
                                <span>Broadcast</span>
                            </button>
                            <button
                                wire:click="makeOffer"
                                class="p-2 rounded bg-surface-container-high hover:bg-surface-variant text-on-surface font-medium flex items-center justify-center gap-1 transition-colors"
                            >
                                <span class="material-symbols-outlined text-sm text-secondary">gavel</span>
                                <span>Make Offer</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Lister Profile & RERA Credentials --}}
                @if($broker)
                    <div class="rounded-xl bg-surface-container p-5 shadow-md flex flex-col gap-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-headline font-semibold text-secondary uppercase tracking-wider">&nbsp;Real Estate Partner</span>
                            <span class="flex items-center gap-1 text-[11px] text-tertiary font-mono">
                                <span class="w-1.5 h-1.5 rounded-full bg-tertiary"></span> {{ $broker->is_online ? 'Online Now' : 'Last seen ' . $broker->last_seen_at?->diffForHumans() }}
                            </span>
                        </div>
                        <div class="flex items-center gap-3.5">
                            <div class="relative">
                                <img
                                    alt="{{ $broker->agency_name }}"
                                    class="w-12 h-12 rounded-full object-cover ring-2 ring-primary"
                                    src="{{ $broker->user->avatar_url ?: asset('build/images/avatar-placeholder.png') }}"
                                />
                                @if($broker->verified)
                                    <span class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-tertiary flex items-center justify-center text-on-tertiary">
                                        <span class="material-symbols-outlined text-[10px]">check</span>
                                    </span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-1.5">
                                    <span class="font-headline font-bold text-sm text-on-surface truncate">{{ $broker->agency_name }}</span>
                                    @if($broker->verified)
                                        <span class="material-symbols-outlined text-tertiary text-xs" style="font-variation-settings: 'FILL' 1;">verified</span>
                                    @endif
                                </div>
                                <span class="text-[11px] text-on-surface-variant font-mono block">{{ $broker->rera_id ? 'RERA Reg: #' . $broker->rera_id : '' }}</span>
                                <div class="flex items-center gap-2 mt-1 text-[11px]">
                                    <span class="text-primary font-bold">{{ $broker->rating }} ★</span>
                                    <span class="text-secondary">({{ $broker->total_deals }}+ deals)</span>
                                    <span class="text-secondary">•</span>
                                    <span class="text-tertiary font-medium">{{ $broker->sla_percent ?? '99.4' }}% SLA</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 pt-1">
                            <button
                                wire:click="callBroker"
                                class="flex-1 py-2 rounded bg-surface-container-high hover:bg-surface-variant text-xs font-semibold text-on-surface flex items-center justify-center gap-1.5 transition-colors"
                            >
                                <span class="material-symbols-outlined text-sm">call</span>
                                <span>Call {{ $broker->masked_phone ?? '+91 XXXXX XXXXX' }}</span>
                            </button>
                            <button
                                wire:click="viewOtherUnits"
                                class="px-3 py-2 rounded bg-surface-container-high hover:bg-surface-variant text-xs text-on-surface font-medium transition-colors"
                            >
                                View {{ $broker->other_units_count ?? 0 }} Other Units
                            </button>
                        </div>
                    </div>
                @endif

                {{-- Specifications & Due Diligence Dossier --}}
                <div class="rounded-xl bg-surface-container p-5 shadow-md flex flex-col gap-4">
                    <span class="text-xs font-headline font-semibold text-secondary uppercase tracking-wider">Specifications &amp; Due Diligence</span>
                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div class="p-3 rounded-lg bg-surface-container-low flex flex-col gap-0.5">
                            <span class="text-secondary text-[10px] uppercase font-mono">Furnishing Condition</span>
                            <span class="text-on-surface font-medium">{{ ucfirst($property->furnishing ?? 'semi') }}-Furnished</span>
                        </div>
                        <div class="p-3 rounded-lg bg-surface-container-low flex flex-col gap-0.5">
                            <span class="text-secondary text-[10px] uppercase font-mono">Possession Timeline</span>
                            <span class="text-tertiary font-medium">
                                @if($property->possession_date && $property->possession_date->isPast())
                                    Immediate / Ready-to-Move
                                @elseif($property->possession_date)
                                    {{ $property->possession_date->format('M Y') }}
                                @else
                                    Contact for Details
                                @endif
                            </span>
                        </div>
                        <div class="p-3 rounded-lg bg-surface-container-low flex flex-col gap-0.5">
                            <span class="text-secondary text-[10px] uppercase font-mono">Water & Power</span>
                            <span class="text-on-surface font-medium">{{ $property->water_power ?? '24/7 + DG Backup' }}</span>
                        </div>
                        <div class="p-3 rounded-lg bg-surface-container-low flex flex-col gap-0.5">
                            <span class="text-secondary text-[10px] uppercase font-mono">Gated Security</span>
                            <span class="text-on-surface font-medium">{{ $property->security ?? '3-Tier Security' }}</span>
                        </div>
                    </div>

                    {{-- Infrastructure Distance HUD --}}
                    <div class="p-3.5 rounded-lg bg-surface-container-low flex flex-col gap-2.5">
                        <span class="text-[11px] font-mono text-secondary uppercase font-semibold">Locality & Transit Proximity</span>
                        <div class="space-y-1.5 text-xs text-on-surface-variant">
                            @foreach($nearbyPlaces ?? [] as $place)
                                <div class="flex items-center justify-between">
                                    <span class="flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-sm {{ $place['icon_color'] ?? 'text-primary' }}">{{ $place['icon'] }}</span>
                                        {{ $place['name'] }}
                                    </span>
                                    <span class="font-mono text-on-surface font-semibold">{{ $place['distance'] }}</span>
                                </div>
                            @endforeach
                            <div class="flex items-center justify-between">
                                <span class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-sm text-tertiary">check_circle</span>
                                    SARFAESI Legal Screening & CIBIL Clear
                                </span>
                                <span class="font-mono text-tertiary font-semibold">{{ $property->legal_status ?? '100% Clear' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
        </div>
    </div>
</div>
