@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ? $title.' · ' : '' }}{{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('shop-icon.svg') }}">
    <link rel="stylesheet" href="{{ asset('auth.css') }}">
    <link rel="stylesheet" href="{{ asset('clean-scrollbars.css') }}">
</head>
<body class="auth">
<div class="auth-shell">
    <section class="auth-aside" aria-hidden="true">
        <a class="auth-brand" href="{{ route('home') }}"><span class="auth-badge">CB</span>{{ config('app.name') }}</a>
        <div>
            <h2>Fresh food, ready when you are.</h2>
            <p>Order from Cecilian Bites for pickup, and manage the restaurant from one secure login.</p>
            <ul class="auth-points">
                <li><svg viewBox="0 0 24 24"><path d="m5 12 5 5L20 7"/></svg>Browse the menu and order for pickup</li>
                <li><svg viewBox="0 0 24 24"><path d="m5 12 5 5L20 7"/></svg>Follow your order from kitchen to counter</li>
                <li><svg viewBox="0 0 24 24"><path d="m5 12 5 5L20 7"/></svg>Pay when you collect</li>
            </ul>
        </div>
        <small>© {{ date('Y') }} {{ config('app.name') }}</small>
    </section>

    <main class="auth-main">
        <div class="auth-card">
            <a class="auth-mobile-brand" href="{{ route('home') }}"><span class="auth-badge">CB</span>{{ config('app.name') }}</a>
            {{ $slot }}
        </div>
    </main>
</div>
<script>
document.querySelectorAll('[data-toggle-password]').forEach(function (button) {
    button.addEventListener('click', function () {
        var input = document.getElementById(button.dataset.togglePassword);
        var show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        button.textContent = show ? 'Hide' : 'Show';
        button.setAttribute('aria-pressed', show ? 'true' : 'false');
    });
});
</script>
</body>
</html>
