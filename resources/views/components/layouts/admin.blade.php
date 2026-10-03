@props(['title' => null])

@use('Illuminate\Support\Str')

@php
    $navigation = [
        ['Dashboard', 'admin.dashboard', 'admin.dashboard'],
        ['Menu', 'admin.foods.index', 'admin.foods.*'],
        ['Categories', 'admin.categories.index', 'admin.categories.*'],
        ['Tables', 'admin.tables.index', 'admin.tables.*'],
        ['Orders', 'admin.orders.index', 'admin.orders.*'],
        ['Reports', 'admin.reports.index', 'admin.reports.*'],
        ['Staff', 'admin.staff.index', 'admin.staff.*'],
    ];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ? $title.' · ' : '' }}Admin · {{ config('app.name') }}</title>

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-linear-to-br from-lime-700/60 via-slate-400 to-slate-500 p-3 font-sans text-neutral-900 antialiased sm:p-6">
        <div class="mx-auto flex max-w-[1500px] flex-col rounded-4xl bg-neutral-900 p-3 shadow-2xl lg:h-[calc(100vh-3rem)]">
            <header class="flex flex-wrap items-center gap-3 px-2 pb-3 pt-1 text-sm text-white">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 pr-2 font-semibold">
                    <x-logo class="size-8" />
                    {{ config('app.name') }}
                </a>

                <nav class="flex max-w-full items-center gap-1 overflow-x-auto rounded-full bg-white/10 p-1">
                    @foreach ($navigation as [$label, $target, $pattern])
                        <a
                            href="{{ route($target) }}"
                            class="shrink-0 rounded-full px-4 py-1.5 {{ request()->routeIs($pattern) ? 'bg-violet-300 font-medium text-neutral-900' : 'hover:bg-white/10' }}"
                        >
                            {{ $label }}
                        </a>
                    @endforeach
                </nav>

                <div class="ml-auto flex items-center gap-2">
                    <a href="{{ route('dashboard') }}" class="rounded-full bg-white/10 px-4 py-1.5 hover:bg-white/20">Open POS</a>

                    <details class="relative">
                        <summary class="flex cursor-pointer list-none items-center gap-3 rounded-full bg-white/10 py-1 pl-4 pr-1 [&::-webkit-details-marker]:hidden">
                            <span>{{ auth()->user()->role->label() }}</span>
                            <span class="grid size-8 place-items-center rounded-full bg-violet-300 text-xs font-semibold text-neutral-900">
                                {{ Str::upper(Str::substr(auth()->user()->name, 0, 1)) }}
                            </span>
                        </summary>

                        <div class="absolute right-0 z-20 mt-2 w-60 rounded-2xl bg-white p-4 text-neutral-900 shadow-xl">
                            <p class="truncate text-sm font-semibold">{{ auth()->user()->name }}</p>
                            <p class="truncate text-xs text-neutral-500">{{ auth()->user()->email }}</p>

                            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                                @csrf
                                <button type="submit" class="w-full rounded-full bg-neutral-900 py-2 text-sm font-medium text-white hover:bg-neutral-700">
                                    Log out
                                </button>
                            </form>
                        </div>
                    </details>
                </div>
            </header>

            <main class="min-h-0 flex-1 overflow-y-auto rounded-3xl bg-linear-to-br from-lime-200 via-lime-100 to-sky-200 p-5">
                @if (session('status'))
                    <p role="status" class="mb-4 rounded-2xl bg-white/80 px-4 py-2.5 text-sm font-medium text-lime-900 ring-1 ring-lime-400">
                        {{ session('status') }}
                    </p>
                @endif

                @foreach (['status', 'category', 'table', 'staff'] as $key)
                    @error($key)
                        <p role="alert" class="mb-4 rounded-2xl bg-rose-100 px-4 py-2.5 text-sm text-rose-700">{{ $message }}</p>
                    @enderror
                @endforeach

                {{ $slot }}
            </main>
        </div>
    </body>
</html>
