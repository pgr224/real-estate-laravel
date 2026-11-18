<footer class="site-footer">
    <div class="site-footer__inner">
        <div class="site-footer__brand">
            <a href="{{ route('home') }}" class="site-brand" aria-label="{{ site_setting('name') }} home">
                <img src="{{ asset('build/images/logo.png') }}" width="40" height="40" alt="">
                <span>{{ site_setting('name') }}</span>
            </a>
        </div>
        <div class="site-footer__contact">
            <h2>Contact details</h2>
            <address>
                @if (site_setting('address'))
                    <div>Address: {{ site_setting('address') }}</div>
                @endif
                @foreach (['phone_01', 'phone_02', 'phone_03'] as $phoneKey)
                    @if (site_setting($phoneKey))
                        <div>
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', (string) site_setting($phoneKey)) }}">
                                {{ site_setting($phoneKey) }}
                            </a>
                        </div>
                    @endif
                @endforeach
            </address>
        </div>
    </div>
    <div class="site-footer__bottom">
        <span title="{{ config('app.version') }}">© {{ now()->year }} {{ site_setting('name') }}. All rights reserved.</span>
        <div class="flex flex-wrap gap-4">
            @foreach (['facebook' => 'Facebook', 'twitter' => 'Twitter', 'github' => 'GitHub', 'youtube' => 'YouTube'] as $key => $label)
                @if (site_setting($key))
                    <a href="{{ site_setting($key) }}" class="hover:text-gray-900" target="_blank" rel="noopener noreferrer">
                        {{ $label }}<span class="sr-only"> (opens in a new tab)</span>
                    </a>
                @endif
            @endforeach
        </div>
    </div>
</footer>
