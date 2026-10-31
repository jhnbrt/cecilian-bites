<x-layouts.guest title="Register">
    <h1>Create your account</h1>
    <p class="auth-sub">Register as a customer to order from Cecilian Bites.</p>

    @if ($errors->any())
        <div class="auth-alert" role="alert">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="auth-form">
        @csrf
        <x-auth-field name="name" label="Full name" placeholder="Juan Dela Cruz" autocomplete="name" required autofocus />
        <x-auth-field name="email" label="Email address" type="email" placeholder="you@example.com" autocomplete="username" required />
        <x-auth-field name="password" label="Password" type="password" placeholder="Create a password" autocomplete="new-password" required />
        <x-auth-field name="password_confirmation" label="Confirm password" type="password" placeholder="Re-enter your password" autocomplete="new-password" required />

        <button class="auth-submit">Create account</button>
    </form>

    <p class="auth-alt">Already have an account? <a href="{{ route('login') }}">Log in</a></p>
</x-layouts.guest>
