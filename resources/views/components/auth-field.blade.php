@props(['name', 'label', 'type' => 'text'])

<div>
    <label for="{{ $name }}" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-neutral-600">{{ $label }}</label>
    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        @if ($type !== 'password') value="{{ old($name) }}" @endif
        {{ $attributes->merge(['class' => 'w-full rounded-xl border-0 bg-white/80 px-4 py-3 text-sm shadow-sm ring-1 ring-violet-200 placeholder:text-neutral-400 focus:outline-none focus:ring-2 focus:ring-violet-400']) }}
    >
    @error($name)
        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
    @enderror
</div>
