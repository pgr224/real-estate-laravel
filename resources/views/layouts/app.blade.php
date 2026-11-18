<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ site_setting('name') }}</title>

    @stack('head')

    @if(config('googletagmanager.id'))
        @include('googletagmanager::head')
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
</head>
<body class="font-sans antialiased">
    @if(config('googletagmanager.id'))
        @include('googletagmanager::body')
    @endif

    <div class="site-shell">
        @include('components.home-navbar')

        <main class="flex-grow">
            @yield('content')
        </main>

        @include('components.footer')
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>
