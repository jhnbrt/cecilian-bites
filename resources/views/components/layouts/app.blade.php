@props(['title' => null])

@use('Illuminate\Support\Str')

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ? $title.' · ' : '' }}{{ config('app.name') }}</title>

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-linear-to-br from-lime-700/60 via-slate-400 to-slate-500 p-3 font-sans text-neutral-900 antialiased sm:p-6">
        <div class="mx-auto flex max-w-[1500px] flex-col rounded-4xl bg-neutral-900 p-3 shadow-2xl lg:h-[calc(100vh-3rem)]">
            <header class="flex flex-wrap items-center gap-3 px-2 pb-3 pt-1 text-sm text-white">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 pr-2 font-semibold">
                    <x-logo class="size-8" />
                    {{ config('app.name') }}
                </a>

                <nav class="flex items-center gap-1 rounded-full bg-white/10 p-1">
                    <a href="{{ route('dashboard') }}" class="rounded-full px-4 py-1.5 hover:bg-white/10">Home</a>
                    <a href="#menu" class="rounded-full bg-violet-300 px-4 py-1.5 font-medium text-neutral-900">Menu</a>
                    <a href="#order-line" class="rounded-full px-4 py-1.5 hover:bg-white/10">Orders</a>
                </nav>

                <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5">
                    @foreach (['type', 'category'] as $filter)
                        @if (request()->filled($filter))
                            <input type="hidden" name="{{ $filter }}" value="{{ request($filter) }}">
                        @endif
                    @endforeach
                    <x-icon name="search" class="size-4 text-white/60" />
                    <input
                        type="search"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Search dishes"
                        class="w-32 border-0 bg-transparent p-0 text-sm text-white placeholder:text-white/50 focus:outline-none sm:w-44"
                    >
                </form>

                <details class="relative ml-auto">
                    <summary class="flex cursor-pointer list-none items-center gap-3 rounded-full bg-white/10 py-1 pl-4 pr-1 [&::-webkit-details-marker]:hidden">
                        <span>{{ auth()->user()->role->label() }}</span>
                        <span class="grid size-8 place-items-center rounded-full bg-violet-300 text-xs font-semibold text-neutral-900">
                            {{ Str::upper(Str::substr(auth()->user()->name, 0, 1)) }}
                        </span>
                    </summary>

                    <div class="absolute right-0 z-20 mt-2 w-60 rounded-2xl bg-white p-4 text-neutral-900 shadow-xl">
                        <p class="truncate text-sm font-semibold">{{ auth()->user()->name }}</p>
                        <p class="truncate text-xs text-neutral-500">{{ auth()->user()->email }}</p>

                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="mt-4 block w-full rounded-full bg-violet-300 py-2 text-center text-sm font-medium text-neutral-900 hover:bg-violet-400">
                                Back to admin panel
                            </a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}" class="mt-3">
                            @csrf
                            <button type="submit" class="w-full rounded-full bg-neutral-900 py-2 text-sm font-medium text-white hover:bg-neutral-700">
                                Log out
                            </button>
                        </form>
                    </div>
                </details>
            </header>

            <div class="grid min-h-0 flex-1 gap-3 lg:grid-cols-[minmax(0,1fr)_21rem]">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
