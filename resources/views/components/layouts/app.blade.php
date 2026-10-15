@props(['title' => null])

@use('Illuminate\Support\Str')

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <link rel="stylesheet" href="{{ asset('cashier-theme.css') }}">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ? $title.' · ' : '' }}{{ config('app.name') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="{{ asset('clean-scrollbars.css') }}">
</head>
    <body class="cashier-body font-sans text-neutral-900 antialiased">
        <div class="cashier-shell">
            <aside class="staff-sidebar">
                <a class="side-brand" href="{{ route('dashboard') }}"><span>◔</span> Cecilian Bites</a>
                <nav aria-label="Cashier navigation" data-side-nav>
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') && !request()->has('q') ? 'selected' : '' }}" data-target="top"><span class="nav-symbol">▦</span> Dashboard</a>
                    <a href="{{ route('dashboard') }}#menu" data-target="menu"><span class="nav-symbol">＋</span> New walk-in order</a>
                    <a href="{{ route('cashier.incoming') }}#order-line" class="{{ request()->routeIs('cashier.incoming') ? 'selected' : '' }}" data-target="order-line"><span class="nav-symbol">▤</span> Incoming orders</a>
                    <a href="{{ route('cashier.transactions') }}" class="{{ request()->routeIs('cashier.transactions') ? 'selected' : '' }}"><span class="nav-symbol">◷</span> Transactions & sales</a>
                    <a href="{{ route('cashier.transactions') }}" ><span class="nav-symbol">₱</span> Payment records</a>
                    @if(auth()->user()->isAdmin())<a href="{{ route('admin.dashboard') }}"><span class="nav-symbol">⚙</span> Admin management</a>@endif
                    <a href="{{ route('home') }}"><span class="nav-symbol">↗</span> Customer website</a>
                </nav>
                <div class="sidebar-note"><h3>Take an order</h3><p>Choose dishes, enter customer details, and place the order. Collect cash, GCash, or Maya payments from the order queue.</p></div>
            </aside>
            <div class="staff-content">
                <header class="staff-topbar">
                    <form method="GET" action="{{ route('dashboard') }}" class="staff-search">
                        @foreach(['type', 'category'] as $filter) @if(request()->filled($filter))<input type="hidden" name="{{ $filter }}" value="{{ request($filter) }}">@endif @endforeach
                        <input type="search" name="q" value="{{ request('q') }}" aria-label="Search food" placeholder="Search food"><button type="submit">Search</button>
                    </form>
                    <details class="staff-account"><summary>{{ auth()->user()->name }} ▾</summary><div><p>{{ auth()->user()->role->label() }}</p><form method="POST" action="{{ route('logout') }}">@csrf<button>Log out</button></form></div></details>
                </header>
                <div class="cashier-workspace">{{ $slot }}</div>
            </div>
        </div>

        <script>
            (function () {
                var links = document.querySelectorAll('[data-side-nav] a[data-target]');
                function select(link) { links.forEach(function (l) { l.classList.toggle('selected', l === link); }); }
                links.forEach(function (link) {
                    link.addEventListener('click', function (e) {
                        var id = link.dataset.target;
                        var el = id === 'top' ? document.querySelector('#pos > .food-workspace') : document.getElementById(id);
                        if (!el) return; // not on the POS page: follow the link normally
                        e.preventDefault();
                        select(link);
                        if (id === 'top') { (document.querySelector('#pos > .food-workspace') || el).scrollTo({ top: 0, behavior: 'smooth' }); history.replaceState(null, '', location.pathname + location.search); }
                        else { el.scrollIntoView({ behavior: 'smooth', block: 'start' }); history.replaceState(null, '', '#' + id); }
                    });
                });
                var hash = location.hash.slice(1);
                var match = hash && Array.prototype.find.call(links, function (l) { return l.dataset.target === hash; });
                if (match) select(match);
            })();
        </script>
    </body>
</html>
