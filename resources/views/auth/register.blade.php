@use('App\Enums\UserRole')

@php($selectedRole = old('role', $role->value))

<x-layouts.guest title="Register">
    <h1 class="text-2xl font-semibold">Create your account</h1>
    <p class="mt-1 text-sm text-neutral-600">Pick your role and set up your {{ config('app.name') }} login.</p>

    <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4" data-role-form>
        @csrf

        <x-role-picker :selected="$selectedRole" label="Register as" />

        <x-auth-field name="name" label="Name" autocomplete="name" required autofocus />
        <x-auth-field name="email" label="Email" type="email" autocomplete="username" required />

        <div data-admin-only @if ($selectedRole !== UserRole::Admin->value) hidden @endif>
            <x-auth-field name="admin_code" label="Admin access code" type="password" autocomplete="off" placeholder="Ask the restaurant owner" />
        </div>

        <x-auth-field name="password" label="Password" type="password" autocomplete="new-password" required />
        <x-auth-field name="password_confirmation" label="Confirm password" type="password" autocomplete="new-password" required />

        <button type="submit" class="w-full rounded-full bg-neutral-900 py-3 text-sm font-semibold text-white transition hover:bg-neutral-700">
            Register
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-neutral-600">
        Already registered?
        <a href="{{ route('login') }}" class="font-semibold text-neutral-900 underline underline-offset-4">Log in</a>
    </p>
</x-layouts.guest>
