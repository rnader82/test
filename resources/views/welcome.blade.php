<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Novalyo 360</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_small.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/demo.css') }}?v=novalyo-html-v02">
</head>
<body class="novalyo-portal novalyo-welcome">
    <div class="portal-welcome">
        <header class="portal-welcome-header">
            <img class="portal-welcome-logo" src="{{ asset('images/logo.png') }}" alt="Novalyo">
            @if (Route::has('login'))
                <nav aria-label="{{ __('locale.portal.home') }}">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-primary">{{ __('locale.portal.dashboard') }}</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary">{{ __('locale.portal.login') }}</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-outline-primary">{{ __('locale.portal.register') }}</a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>
        <main class="portal-welcome-card">
            <span style="color: #91378e; font-weight: 700; letter-spacing: .12em; font-size: 11px;">NOVALYO 360</span>
            <h1>{{ __('locale.portal.welcome') }}</h1>
            <p>{{ __('locale.portal.welcome_text') }}</p>
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary">{{ __('locale.portal.dashboard') }}</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">{{ __('locale.portal.login') }}</a>
                @endauth
            @endif
        </main>
    </div>
</body>
</html>
