<x-layouts.guest title="Register">
    <h1 class="text-2xl font-semibold">Create your account</h1>
    <p class="mt-2 text-sm text-neutral-600">Register as a customer to order from Cecilian Bites.</p>
    @if ($errors->any())<div class="mt-4 rounded-xl bg-red-50 p-3 text-sm text-red-700">{{ $errors->first() }}</div>@endif
    <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
        @csrf
        <x-auth-field name="name" label="Full name" autocomplete="name" required autofocus />
        <x-auth-field name="email" label="Email address" type="email" autocomplete="username" required />
        <x-auth-field name="password" label="Password" type="password" autocomplete="new-password" required />
        <x-auth-field name="password_confirmation" label="Confirm password" type="password" autocomplete="new-password" required />
        <button class="w-full rounded-full bg-orange-600 py-3 font-semibold text-white hover:bg-orange-700">Register</button>
    </form>
    <p class="mt-5 text-center text-sm">Already have an account? <a class="font-semibold text-orange-700 underline" href="{{ route('login') }}">Log in</a></p>
</x-layouts.guest>
