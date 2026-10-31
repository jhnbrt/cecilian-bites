<x-layouts.guest title="Log in">
    <h1>Welcome back</h1>
    <p class="auth-sub">Log in to order food or manage the restaurant.</p>

    @if ($errors->any())
        <div class="auth-alert" role="alert">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="auth-form">
        @csrf
        <x-auth-field name="email" label="Email address" type="email" placeholder="you@example.com" autocomplete="username" required autofocus />
        <x-auth-field name="password" label="Password" type="password" placeholder="Enter your password" autocomplete="current-password" required />

        <div class="auth-row">
            <label class="auth-check"><input type="checkbox" name="remember" value="1"> Remember me</label>
        </div>

        <button class="auth-submit">Log in</button>
    </form>

    <p class="auth-alt">New customer? <a href="{{ route('register') }}">Create an account</a></p>
    <p class="auth-note">Cashier accounts are provided by your administrator.</p>
</x-layouts.guest>
