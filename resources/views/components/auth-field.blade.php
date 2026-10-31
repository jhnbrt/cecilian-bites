@props(['name', 'label', 'type' => 'text'])

<div class="auth-field">
    <label for="{{ $name }}">{{ $label }}</label>
    <div @class(['auth-input', 'has-error' => $errors->has($name)])>
        <input
            id="{{ $name }}"
            name="{{ $name }}"
            type="{{ $type }}"
            @if ($type !== 'password') value="{{ old($name) }}" @endif
            {{ $attributes }}
        >
        @if ($type === 'password')
            <button type="button" class="auth-toggle" data-toggle-password="{{ $name }}" aria-pressed="false">Show</button>
        @endif
    </div>
    @error($name)
        <p class="auth-error">{{ $message }}</p>
    @enderror
</div>
