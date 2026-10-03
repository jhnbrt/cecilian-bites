<x-layouts.guest title="Log in">
    <h1 class="text-2xl font-semibold">Welcome back</h1>
    <p class="mt-1 text-sm text-neutral-600">Log in to start taking orders.</p>

    <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
        @csrf

        <x-role-picker :selected="old('role', $role->value)" label="Log in as" />

        <x-auth-field name="email" label="Email" type="email" autocomplete="username" required autofocus />
        <x-auth-field name="password" label="Password" type="password" autocomplete="current-password" required />

        <label class="flex items-center gap-2 text-sm text-neutral-700">
            <input type="checkbox" name="remember" value="1" class="size-4 rounded accent-violet-500">
            Remember me
        </label>

        <button type="submit" class="w-full rounded-full bg-neutral-900 py-3 text-sm font-semibold text-white transition hover:bg-neutral-700">
            Log in
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-neutral-600">
        New here?
        <a href="{{ route('register') }}" class="font-semibold text-neutral-900 underline underline-offset-4">Create an account</a>
    </p>
</x-layouts.guest>
