<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="application-name" content="{{ config('app.name') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover" />
    <meta name="shell-type" content="mobile_tab" />

    <title>{{ $title ?? 'ReelEstate AI Marketplace' }}</title>

    {{-- Google Fonts: Geist + Material Symbols --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />

    <style>
        [x-cloak] { display: none !important; }
        /* Stitch M3 base styles */
        html, body {
            width: 100%;
            margin: 0;
            padding: 0;
        }
        body {
            overscroll-behavior-y: none;
            -webkit-tap-highlight-color: transparent;
            background-color: #09090b;
            min-height: 100dvh;
        }
        .pb-safe { padding-bottom: env(safe-area-inset-bottom, 0px); }
        .pt-safe { padding-top: env(safe-area-inset-top, 0px); }
        main > :first-child { margin-top: 0 !important; }
        main > :last-child { margin-bottom: 0 !important; }
        /* Hide scrollbars (Stitch style) */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        /* Material Symbols defaults */
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>

    @filamentStyles
    @vite('resources/css/app.css')
    @stack('styles')
</head>
<body class="bg-background font-body text-on-surface flex flex-col min-h-screen selection:bg-primary selection:text-on-primary antialiased">
    {{-- Top App Bar / Brand Header --}}
    <header class="sticky top-0 w-full z-40 bg-background/90 backdrop-blur-xl border-b border-outline-variant/30 shadow-[0_1px_8px_rgba(0,0,0,0.5)]">
        <div class="pt-safe">
            <div class="h-14 px-4 flex items-center justify-between gap-3 max-w-7xl mx-auto">
                <a href="/" class="flex items-center gap-2 group">
                    <div class="w-8 h-8 rounded-lg bg-primary/15 border border-primary/30 flex items-center justify-center text-primary group-hover:scale-105 transition-transform">
                        <span class="material-symbols-outlined text-[20px]">apartment</span>
                    </div>
                    <span class="font-headline font-bold text-sm tracking-tight text-on-surface">
                        Reel<span class="text-primary">Estate</span>
                        <span class="text-[9px] text-tertiary font-semibold ml-1 px-1.5 py-0.5 rounded bg-tertiary-container/30 uppercase tracking-wider">AI</span>
                    </span>
                </a>
                
                <div class="flex items-center gap-2">
                    <a href="/map" class="text-xs font-medium px-2.5 py-1.5 rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface flex items-center gap-1 transition-colors">
                        <span class="material-symbols-outlined text-[15px] text-primary">location_on</span>
                        <span class="truncate max-w-[90px]">SG Highway</span>
                    </a>
                    <a href="/auctions/live" class="text-xs font-semibold px-2.5 py-1.5 rounded-lg bg-primary/20 hover:bg-primary/30 text-primary border border-primary/30 flex items-center gap-1 transition-colors">
                        <span class="material-symbols-outlined text-[15px]">gavel</span>
                        <span class="hidden sm:inline">Live</span> Auction
                    </a>
                    <a href="/legacy-home" title="Switch to Classic View" class="text-xs p-1.5 rounded-lg text-on-surface-variant hover:text-on-surface hover:bg-surface-container transition-colors flex items-center">
                        <span class="material-symbols-outlined text-[18px]">tune</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    {{-- Main View Container --}}
    <main class="flex-1 w-full pb-20">
        {{ $slot }}
    </main>

    {{-- Floating / Fixed Bottom Navigation Bar (Stitch M3 Design) --}}
    <nav class="fixed bottom-0 left-0 right-0 z-50 pb-safe bg-surface-container-lowest/95 backdrop-blur-xl border-t border-outline-variant/30 shadow-[0_-1px_12px_rgba(0,0,0,0.6)]">
        <div class="flex justify-around items-center h-16 px-2 max-w-lg mx-auto">
            <a href="/" class="flex flex-col items-center justify-center gap-0.5 min-w-[56px] min-h-[44px] transition-all {{ request()->is('/') || request()->is('marketplace*') ? 'text-primary font-semibold' : 'text-on-surface-variant hover:text-on-surface' }}">
                <span class="material-symbols-outlined text-[22px]">explore</span>
                <span class="text-[10px] font-label tracking-tight">Explore</span>
            </a>
            <a href="/reels" class="flex flex-col items-center justify-center gap-0.5 min-w-[56px] min-h-[44px] transition-all {{ request()->is('reels*') ? 'text-primary font-semibold' : 'text-on-surface-variant hover:text-on-surface' }}">
                <span class="material-symbols-outlined text-[22px]">play_circle</span>
                <span class="text-[10px] font-label tracking-tight">Reels</span>
            </a>
            <a href="/map" class="flex flex-col items-center justify-center gap-0.5 min-w-[56px] min-h-[44px] transition-all {{ request()->is('map*') ? 'text-primary font-semibold' : 'text-on-surface-variant hover:text-on-surface' }}">
                <span class="material-symbols-outlined text-[22px]">map</span>
                <span class="text-[10px] font-label tracking-tight">Map</span>
            </a>
            <a href="/builders" class="flex flex-col items-center justify-center gap-0.5 min-w-[56px] min-h-[44px] transition-all {{ request()->is('builders*') ? 'text-primary font-semibold' : 'text-on-surface-variant hover:text-on-surface' }}">
                <span class="material-symbols-outlined text-[22px]">domain</span>
                <span class="text-[10px] font-label tracking-tight">Builders</span>
            </a>
            <a href="/brokers" class="flex flex-col items-center justify-center gap-0.5 min-w-[56px] min-h-[44px] transition-all {{ request()->is('brokers*') ? 'text-primary font-semibold' : 'text-on-surface-variant hover:text-on-surface' }}">
                <span class="material-symbols-outlined text-[22px]">badge</span>
                <span class="text-[10px] font-label tracking-tight">Brokers</span>
            </a>
        </div>
    </nav>

    @livewire('notifications')

    @filamentScripts
    @vite('resources/js/app.js')
    @stack('scripts')
</body>
</html>
