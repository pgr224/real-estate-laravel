<footer class="site-footer">
    <div class="site-footer__inner">
        <div class="site-footer__brand">
            <a href="{{ route('home') }}" class="site-brand" aria-label="{{ SiteConfig::get('name') }} home">
                <img src="{{ asset('build/images/logo.png') }}" width="40" height="40" alt="">
                <span>{{ SiteConfig::get('name') }}</span>
            </a>
        </div>
        <div class="site-footer__contact">
            <h2>Contact details</h2>
            <address>
                @if (SiteConfig::get('address'))
                    <div>Address: {{ SiteConfig::get('address') }}</div>
                @endif
                @foreach (['phone_01', 'phone_02', 'phone_03'] as $phoneKey)
                    @if (SiteConfig::get($phoneKey))
                        <div>
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', (string) SiteConfig::get($phoneKey)) }}">
                                {{ SiteConfig::get($phoneKey) }}
                            </a>
                        </div>
                    @endif
                @endforeach
            </address>
        </div>
    </div>
    <div class="site-footer__bottom">
        <span title="{{ config('app.version') }}">© {{ now()->year }} {{ SiteConfig::get('name') }}. All rights reserved.</span>
        <div class="flex flex-wrap gap-4">
            @foreach (['facebook' => 'Facebook', 'twitter' => 'Twitter', 'github' => 'GitHub', 'youtube' => 'YouTube'] as $key => $label)
                @if (SiteConfig::get($key))
                    <a href="{{ SiteConfig::get($key) }}" class="hover:text-gray-900" target="_blank" rel="noopener noreferrer">
                        {{ $label }}<span class="sr-only"> (opens in a new tab)</span>
                    </a>
                @endif
            @endforeach
        </div>
    </div>
</footer>
