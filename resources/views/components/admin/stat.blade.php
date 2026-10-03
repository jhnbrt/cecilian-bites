@props(['label', 'value', 'hint' => null])

<div class="rounded-2xl bg-white p-4 shadow-sm">
    <p class="text-xs font-medium text-neutral-500">{{ $label }}</p>
    <p class="mt-1 text-2xl font-semibold">{{ $value }}</p>
    @if ($hint)
        <p class="mt-0.5 text-[11px] text-neutral-400">{{ $hint }}</p>
    @endif
</div>
