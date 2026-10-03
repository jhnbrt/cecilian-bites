@props(['title', 'subtitle' => null])

<div class="mb-5 flex flex-wrap items-center gap-3">
    <div>
        <h1 class="text-xl font-semibold">{{ $title }}</h1>
        @if ($subtitle)
            <p class="text-sm text-neutral-600">{{ $subtitle }}</p>
        @endif
    </div>

    @if (! $slot->isEmpty())
        <div class="ml-auto flex flex-wrap items-center gap-2">
            {{ $slot }}
        </div>
    @endif
</div>
