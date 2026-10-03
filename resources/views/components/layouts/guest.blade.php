@props(['title' => null])

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
    <body class="min-h-screen bg-linear-to-br from-lime-700/60 via-slate-400 to-slate-500 font-sans text-neutral-900 antialiased">
        <main class="flex min-h-screen items-center justify-center p-4">
            <div class="w-full max-w-md rounded-4xl bg-neutral-900 p-2.5 shadow-2xl">
                <div class="rounded-3xl bg-linear-to-br from-lime-200 via-lime-100 to-sky-200 p-8">
                    <a href="{{ route('home') }}" class="mb-6 flex items-center gap-2 font-semibold">
                        <x-logo class="size-9" />
                        {{ config('app.name') }}
                    </a>

                    {{ $slot }}
                </div>
            </div>
        </main>
    </body>
</html>
