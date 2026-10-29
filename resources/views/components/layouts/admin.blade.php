@props(['title' => null])
@use('Illuminate\Support\Str')
@php
    $navigation = [
        ['Dashboard', 'admin.dashboard', 'admin.dashboard', '▦'],
        ['Products', 'admin.foods.index', 'admin.foods.*', '◫'],
        ['Categories', 'admin.categories.index', 'admin.categories.*', '▤'],
        ['Tables', 'admin.tables.index', 'admin.tables.*', '▧'],
        ['Orders', 'admin.orders.index', 'admin.orders.*', '▣'],
        ['Sales & Reports', 'admin.reports.index', 'admin.reports.*', '◷'],
        ['Staff', 'admin.staff.index', 'admin.staff.*', '♙'],
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ? $title.' · ' : '' }}Admin · {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('clean-scrollbars.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-dark.css') }}">
</head>
<body class="cb-admin-body">
<div class="cb-admin-shell">
    <aside class="cb-admin-sidebar">
        <a href="{{ route('admin.dashboard') }}" class="cb-admin-brand"><span class="cb-brand-mark">✳</span><span><strong>Cecilian Bites</strong><small>ADMIN DASHBOARD</small></span></a>
        <div class="cb-nav-label">WORKSPACE</div>
        <nav class="cb-admin-nav" aria-label="Admin navigation">
            @foreach($navigation as [$label, $target, $pattern, $symbol])
                <a href="{{ route($target) }}" class="{{ request()->routeIs($pattern) ? 'is-active' : '' }}"><span class="cb-nav-icon">{{ $symbol }}</span>{{ $label }}</a>
            @endforeach
        </nav>
        <div class="cb-sidebar-bottom"><a href="{{ route('dashboard') }}" class="cb-pos-link">↗ &nbsp; Open cashier POS</a><form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="cb-sidebar-logout">↪ &nbsp; Log out</button></form></div>
    </aside>
    <div class="cb-admin-main">
        <header class="cb-admin-topbar"><div><strong>{{ $title ?? 'Admin' }}</strong><small>{{ now()->format('l, F j, Y') }}</small></div><div class="cb-admin-account"><span class="cb-account-avatar">{{ Str::upper(Str::substr(auth()->user()->name, 0, 1)) }}</span><span>{{ auth()->user()->name }}</span><span class="cb-account-role">{{ auth()->user()->role->label() }}</span></div></header>
        <main class="cb-admin-content">
            @if(session('status'))<div class="cb-alert">{{ session('status') }}</div>@endif
            @foreach(['status','category','table','staff'] as $key) @error($key)<div class="cb-alert">{{ $message }}</div>@enderror @endforeach
            {{ $slot }}
        </main>
    </div>
</div>
</body></html>
