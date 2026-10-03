@props(['selected', 'label' => 'I am a'])

@use('App\Enums\UserRole')

<div>
    <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-neutral-600">{{ $label }}</span>

    <div class="flex rounded-full bg-white/60 p-1 text-sm ring-1 ring-violet-200">
        @foreach (UserRole::cases() as $case)
            <label class="flex-1 cursor-pointer rounded-full px-3 py-2 text-center font-medium transition has-[:checked]:bg-neutral-900 has-[:checked]:text-white">
                <input type="radio" name="role" value="{{ $case->value }}" class="sr-only" @checked($selected === $case->value)>
                {{ $case->label() }}
            </label>
        @endforeach
    </div>

    @error('role')
        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
    @enderror
</div>
