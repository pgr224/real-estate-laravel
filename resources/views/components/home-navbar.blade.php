@props([
    'user' => null,
    'role' => null,
    'dashboardUrl' => null,
])
@if (auth()->check())
    @php
        $user = auth()->user();
        $role = $user->getRoleNames()->first() ?? 'user';
        $dashboardUrl = $role === 'admin' ? '/admin' : '/' . $role;
    @endphp
@endif
<header class="site-header">
    <nav class="site-nav" aria-label="Main navigation">
        <div class="site-nav__inner">
            <a href="{{ route('home') }}" class="site-brand" aria-label="{{ site_setting('name') }} home">
                <img src="{{ asset('build/images/logo.png') }}" width="40" height="40" alt="" fetchpriority="high" />
                <span>{{ site_setting('name') }}</span>
        </a>


            <div class="site-nav__links" id="site-navigation">
                <ul>{!! app(App\Services\MenuService::class)->buildMenu() !!}</ul>
            </div>

            <div class="site-nav__actions">
            @if (auth()->check())
                @php
                    $user = auth()->user();
                    $role = $user->getRoleNames()->first() ?? 'user';
                    $dashboardUrl = $role === 'admin' ? '/admin' : '/' . $role;
                @endphp

                <a href="{{ $dashboardUrl }}" class="site-nav__account">
                    {{ ucfirst($role) }} dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="site-nav__account">Log in</a>
                <a href="{{ route('register') }}" class="site-nav__register">Create account</a>
            @endif

                <button id="menuToggleButton" class="site-nav__toggle" type="button"
                    aria-controls="site-navigation" aria-expanded="false" aria-label="Open main menu">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                    </svg>
                </button>
            </div>
        </div>
    </nav>
</header>
