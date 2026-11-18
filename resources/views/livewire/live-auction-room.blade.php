<div wire:poll.2s="loadAuction"
     x-data="{
        secondsRemaining: {{ $auction ? $auction->end_time->diffInSeconds(now()) : 872 }},
        proposedBid: {{ $auction ? (($auction->current_bid ?? $auction->starting_price) + ($auction->minimum_increment ?? 100000)) : 23500000 }},
        formatIndian(val) {
            return val.toLocaleString('en-IN');
        },
        setBidDelta(delta) {
            this.proposedBid = {{ $auction ? ($auction->current_bid ?? $auction->starting_price ?? 0) : 23400000 }} + delta;
        },
        resetBid() {
            this.proposedBid = {{ $auction ? (($auction->current_bid ?? $auction->starting_price) + ($auction->minimum_increment ?? 100000)) : 23500000 }};
        },
        init() {
            setInterval(() => {
                if (this.secondsRemaining > 0) {
                    this.secondsRemaining--;
                }
            }, 1000);
        },
        get timerDisplay() {
            let hrs = Math.floor(this.secondsRemaining / 3600);
            let mins = Math.floor((this.secondsRemaining % 3600) / 60);
            let secs = this.secondsRemaining % 60;
            return String(hrs).padStart(2, '0') + ':' + String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0') + ' left';
        }
     }"
>
    {{-- Live Status & Countdown Banner --}}
    <div class="px-4 py-2.5 bg-surface-container flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2">
            <span class="relative flex h-2.5 w-2.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-error opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-error"></span>
            </span>
            <span class="text-xs font-semibold tracking-wider text-error uppercase">
                {{ $auction->is_live ? 'LIVE BIDDING NOW' : 'AUCTION ENDED' }}
            </span>
        </div>
        <div class="flex items-center gap-1.5 bg-surface-container-high px-2.5 py-1 rounded-full">
            <span class="material-symbols-outlined text-primary text-[15px]">timer</span>
            <span class="text-xs font-mono font-medium text-on-surface" x-text="timerDisplay"></span>
        </div>
    </div>

    {{-- Main Showcase Bento --}}
    <div class="px-4 pt-4 flex flex-col gap-4">
        {{-- Property Card --}}
        <div class="bg-surface-container rounded-xl overflow-hidden shadow-sm flex flex-col">
            <div class="relative w-full h-52">
                <img class="w-full h-full object-cover" src="{{ $auction->property->getFirstMediaUrl('images') ?: asset('build/images/property-placeholder.png') }}" alt="{{ $auction->property->title }}">
                <div class="absolute inset-0 bg-gradient-to-t from-surface-container via-surface-container/30 to-transparent"></div>
                <div class="absolute top-3 left-3 bg-surface-container-lowest/80 backdrop-blur-md px-2.5 py-1 rounded-full flex items-center gap-1.5 text-xs text-on-surface font-medium">
                    <span class="material-symbols-outlined text-[14px] text-tertiary">gavel</span>
                    <span>{{ $auction->act_reference ?? 'SARFAESI Act 2002' }}</span>
                </div>
                @if($auction->possession_status ?? false)
                    <div class="absolute top-3 right-3 bg-tertiary-container/90 backdrop-blur-md px-2.5 py-1 rounded-full text-xs font-semibold text-tertiary-fixed flex items-center gap-1">
                        <span class="material-symbols-outlined text-[13px]">key</span>
                        <span>{{ $auction->possession_status }}</span>
                    </div>
                @endif
                <div class="absolute bottom-3 left-3 right-3">
                    <div class="flex items-center gap-1.5 text-xs text-secondary-fixed mb-1">
                        <span class="material-symbols-outlined text-[14px] text-primary">account_balance</span>
                        <span class="font-medium truncate">{{ $auction->bank_name ?? '' }} &bull; {{ $auction->branch ?? '' }}</span>
                    </div>
                    <h2 class="text-base font-semibold text-on-surface tracking-tight leading-snug">
                        {{ $auction->property->title }}
                    </h2>
                    <p class="text-xs text-on-surface-variant flex items-center gap-1 mt-0.5">
                        <span class="material-symbols-outlined text-[13px]">pin_drop</span>
                        {{ $auction->property->full_address ?? $auction->property->location }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Live Highest Bid Highlight Banner --}}
        <div class="bg-gradient-to-r from-surface-container-high via-surface-container to-surface-container-high rounded-xl p-3.5 shadow-md flex items-center justify-between">
            <div>
                <div class="flex items-center gap-1 text-[11px] font-medium text-on-surface-variant tracking-wide uppercase">
                    <span>Current Highest Bid</span>
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-tertiary"></span>
                </div>
                <div class="text-2xl font-mono font-bold text-on-surface tracking-tight mt-0.5">
                    ₹{{ number_format($currentHighestBid ?? 0) }}
                </div>
                <div class="text-[11px] text-secondary-fixed flex items-center gap-1 mt-0.5">
                    <span>Leading Bidder:</span>
                    <span class="font-mono text-primary font-medium">#{{ $leadingBidderId ?? 'N/A' }}</span>
                    @if($isLeadingBidder ?? false)
                        <span class="text-tertiary text-[10px] ml-1 bg-tertiary-container/40 px-1.5 py-0.2 rounded">You!</span>
                    @else
                        <span class="text-tertiary text-[10px] ml-1 bg-tertiary-container/40 px-1.5 py-0.2 rounded">Active</span>
                    @endif
                </div>
            </div>
            <div class="flex flex-col items-end">
                @if($discountPercent ?? false)
                    <span class="text-[10px] uppercase font-bold text-tertiary bg-tertiary-container/30 px-2 py-0.5 rounded-full mb-1">{{ $discountPercent }}% Below Market</span>
                @endif
                <span class="text-xs text-on-surface-variant line-through font-mono">Market: ₹{{ number_format($marketPrice ?? 0) }}</span>
                <span class="text-xs text-secondary-fixed font-mono">Reserve: ₹{{ number_format($reservePrice ?? 0) }}</span>
            </div>
        </div>

        {{-- Key Financials & Specs Grid --}}
        <div class="grid grid-cols-2 gap-2">
            <div class="bg-surface-container rounded-lg p-3">
                <div class="text-[11px] text-on-surface-variant">EMD Deposited</div>
                <div class="text-sm font-semibold font-mono text-on-surface mt-0.5">₹{{ number_format(($emdAmount ?? 0) / 100000, 2) }} Lakhs</div>
                <div class="text-[10px] text-tertiary mt-1 flex items-center gap-0.5">
                    <span class="material-symbols-outlined text-[12px]">verified</span> {{ $emdStatus ?? 'Escrow Locked' }}
                </div>
            </div>
            <div class="bg-surface-container rounded-lg p-3">
                <div class="text-[11px] text-on-surface-variant">Min. Bid Step</div>
                <div class="text-sm font-semibold font-mono text-on-surface mt-0.5">₹{{ number_format($minBidStep ?? 100000) }}</div>
                <div class="text-[10px] text-secondary-fixed mt-1 flex items-center gap-0.5">
                    <span class="material-symbols-outlined text-[12px]">trending_up</span> Fixed Increment
                </div>
            </div>
            <div class="bg-surface-container rounded-lg p-3 col-span-2 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-md bg-tertiary-container/40 flex items-center justify-center text-tertiary">
                        <span class="material-symbols-outlined text-[18px]">verified_user</span>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-on-surface">{{ $auction->possession_status ?? 'Physical Possession Vacant' }}</div>
                        <div class="text-[10px] text-on-surface-variant">{{ $auction->possession_detail ?? 'No tenant encumbrance • Spot keys handover' }}</div>
                    </div>
                </div>
                <span class="material-symbols-outlined text-tertiary text-[18px]">check_circle</span>
            </div>
        </div>

        {{-- AI Legal Risk Score Card --}}
        <div class="bg-surface-container rounded-xl p-4 shadow-sm">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-md bg-primary-container/40 text-primary flex items-center justify-center">
                        <span class="material-symbols-outlined text-[16px]">smart_toy</span>
                    </div>
                    <div>
                        <h3 class="text-xs font-semibold text-on-surface tracking-tight">Proprietary AI Title Audit</h3>
                        <span class="text-[10px] text-on-surface-variant font-mono">Model v4.2 &bull; Updated {{ $aiAuditUpdatedAgo ?? '40m ago' }}</span>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-base font-bold font-mono text-tertiary">{{ $legalRiskScore ?? 94 }}<span class="text-xs font-normal text-secondary">/100</span></span>
                    <div class="text-[10px] font-medium text-tertiary leading-none">{{ $legalRiskLabel ?? 'Low Legal Risk' }}</div>
                </div>
            </div>
            {{-- Trust Badges List --}}
            <div class="mt-3 flex flex-col gap-2">
                @foreach($trustBadges ?? [] as $badge)
                    <div class="flex items-center justify-between bg-surface-container-high px-2.5 py-2 rounded-lg text-xs">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-tertiary text-[15px]">done_all</span>
                            <span class="text-on-surface">{{ $badge->label }}</span>
                        </div>
                        <span class="text-[10px] text-secondary-fixed font-mono">{{ $badge->status }}</span>
                    </div>
                @endforeach
            </div>
            {{-- Download Title Pack CTA --}}
            <button wire:click="downloadTitlePack" class="mt-3 w-full bg-surface-container-high hover:bg-surface-container-highest transition-colors rounded-lg py-2 px-3 flex items-center justify-center gap-2 text-xs font-medium text-primary">
                <span class="material-symbols-outlined text-[16px]">file_download</span>
                <span>Download Title &amp; Encumbrance Pack (PDF)</span>
            </button>
        </div>

        {{-- Real-time Bid History Stream --}}
        <div class="bg-surface-container rounded-xl p-3.5 shadow-sm">
            <div class="flex items-center justify-between pb-2">
                <div class="flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-primary text-[15px]">sensors</span>
                    <span class="text-xs font-semibold text-on-surface tracking-tight">Real-Time Bidding Stream</span>
                </div>
                <span class="text-[10px] font-mono text-tertiary flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-tertiary animate-pulse"></span>
                    Live Sync
                </span>
            </div>
            <div class="flex flex-col gap-2 mt-1">
                @foreach($bidHistory ?? [] as $index => $bid)
                    <div @class([
                        'flex items-center justify-between p-2 rounded-lg',
                        'bg-surface-container-high' => $index === 0,
                        'bg-surface-container-low opacity-85' => $index === 1,
                        'bg-surface-container-low opacity-70' => $index >= 2,
                    ])>
                        <div class="flex items-center gap-2">
                            <div @class([
                                'w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-mono font-bold',
                                'bg-primary/20 text-primary' => $index === 0,
                                'bg-secondary-container text-on-surface-variant' => $index > 0,
                                'bg-tertiary-container text-tertiary' => $bid->is_own_bid ?? false,
                            ])>
                                {{ $bid->is_own_bid ? 'YOU' : substr($bid->bidder_id, -2) }}
                            </div>
                            <div>
                                <div class="text-xs font-medium text-on-surface">
                                    {{ $bid->is_own_bid ? 'Your Bid Confirmed' : 'Bidder #' . $bid->bidder_id }}
                                </div>
                                <div @class([
                                    'text-[10px] font-medium',
                                    'text-tertiary' => $index === 0 || ($bid->is_own_bid ?? false),
                                    'text-on-surface-variant' => $index > 0 && !($bid->is_own_bid ?? false),
                                ])>
                                    {{ $index === 0 ? 'New Highest Offer' : 'Outbid' }}
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div @class([
                                'text-xs font-mono font-bold',
                                'text-on-surface' => $index === 0,
                                'text-on-surface-variant' => $index > 0,
                            ])>₹{{ number_format($bid->amount) }}</div>
                            <div class="text-[10px] text-on-surface-variant font-mono">{{ $bid->time_ago }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Quick Bidding Controller --}}
        @if($auction->is_live && ($isRegistered ?? false))
            <div class="bg-surface-container rounded-xl p-4 shadow-lg flex flex-col gap-3">
                <div class="flex items-center justify-between">
                    <label class="text-xs font-semibold text-on-surface flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-primary text-[15px]">touch_app</span>
                        Quick Bid Increment
                    </label>
                    <span class="text-[11px] text-secondary-fixed">Next minimum: ₹<span x-text="formatIndian({{ $nextMinimumBid ?? 23500000 }})"></span></span>
                </div>
                {{-- Increment Buttons --}}
                <div class="grid grid-cols-3 gap-2">
                    <button @click="setBidDelta(100000)" class="bg-surface-container-high hover:bg-surface-container-highest active:bg-primary-container text-on-surface rounded-lg py-2 px-1 text-center font-mono text-xs font-semibold transition-all">
                        +₹1.0 Lakh
                    </button>
                    <button @click="setBidDelta(250000)" class="bg-surface-container-high hover:bg-surface-container-highest active:bg-primary-container text-on-surface rounded-lg py-2 px-1 text-center font-mono text-xs font-semibold transition-all">
                        +₹2.5 Lakh
                    </button>
                    <button @click="setBidDelta(500000)" class="bg-surface-container-high hover:bg-surface-container-highest active:bg-primary-container text-on-surface rounded-lg py-2 px-1 text-center font-mono text-xs font-semibold transition-all">
                        +₹5.0 Lakh
                    </button>
                </div>
                {{-- Custom Input Box --}}
                <div class="relative flex items-center bg-surface-container-high rounded-lg px-3 py-2">
                    <span class="text-sm font-semibold font-mono text-primary mr-1">₹</span>
                    <input
                        x-model="proposedBid"
                        @input="proposedBid = parseInt($event.target.value.replace(/[^0-9]/g, '')) || 0"
                        class="bg-transparent w-full text-on-surface font-mono font-bold text-sm focus:outline-none tracking-wide"
                        type="text"
                        :value="formatIndian(proposedBid)"
                    >
                    <button @click="resetBid()" class="text-[11px] text-secondary-fixed hover:text-on-surface ml-2">
                        Reset
                    </button>
                </div>
                {{-- High-Voltage Place Bid Action Button --}}
                <button
                    @click="$wire.set('bidAmount', proposedBid).then(() => $wire.placeBid())"
                    class="w-full bg-primary hover:bg-primary-fixed-dim active:scale-[0.99] text-on-primary font-semibold py-3.5 px-4 rounded-xl flex items-center justify-center gap-2 shadow-md transition-all text-sm tracking-tight"
                >
                    <span class="material-symbols-outlined text-[19px]">gavel</span>
                    <span>Confirm &amp; Place Bid ₹<span x-text="formatIndian(proposedBid)"></span></span>
                </button>
                {{-- Security / Legal Disclaimer --}}
                <div class="flex items-start gap-1.5 text-[10px] text-on-surface-variant leading-tight">
                    <span class="material-symbols-outlined text-[13px] text-secondary flex-shrink-0 mt-0.5">shield</span>
                    <span>
                        Legally binding offer under SARFAESI Act 2002. Forfeiture of deposited EMD applies if final winning bid is revoked or defaulted.
                    </span>
                </div>
            </div>
        @elseif(!($isRegistered ?? false))
            {{-- EMD Registration Prompt --}}
            <div class="bg-surface-container rounded-xl p-4 shadow-lg flex flex-col gap-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-md bg-primary-container/40 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[18px]">how_to_reg</span>
                    </div>
                    <div>
                        <h3 class="text-xs font-semibold text-on-surface">Register to Bid</h3>
                        <p class="text-[10px] text-on-surface-variant">EMD deposit required to participate in this auction</p>
                    </div>
                </div>
                <div class="bg-surface-container-high rounded-lg p-3 flex items-center justify-between">
                    <div>
                        <div class="text-[11px] text-on-surface-variant">Required EMD Amount</div>
                        <div class="text-sm font-semibold font-mono text-on-surface mt-0.5">₹{{ number_format(($requiredEmd ?? 0) / 100000, 2) }} Lakhs</div>
                    </div>
                    <div class="text-[10px] text-on-surface-variant">{{ $emdPercentage ?? '10%' }} of Reserve Price</div>
                </div>
                <button wire:click="registerForAuction" class="w-full bg-primary hover:bg-primary-fixed-dim text-on-primary font-semibold py-3 px-4 rounded-xl flex items-center justify-center gap-2 shadow-md transition-all text-sm">
                    <span class="material-symbols-outlined text-[18px]">gavel</span>
                    <span>Register &amp; Deposit EMD</span>
                </button>
                <div class="flex items-start gap-1.5 text-[10px] text-on-surface-variant leading-tight">
                    <span class="material-symbols-outlined text-[13px] text-tertiary flex-shrink-0 mt-0.5">info</span>
                    <span>EMD is fully refundable if you do not win the auction. Escrow-secured via RBI-compliant payment gateway.</span>
                </div>
            </div>
        @endif
    </div>
</div>
