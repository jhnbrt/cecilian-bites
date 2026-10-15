<x-layouts.guest title="Log in">
    <h1 class="text-2xl font-semibold">Welcome back</h1>
    <p class="mt-2 text-sm text-neutral-600">One secure login for customers and Cecilian Bites staff.</p>
    @if ($errors->any())<div class="mt-4 rounded-xl bg-red-50 p-3 text-sm text-red-700">{{ $errors->first() }}</div>@endif
    <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
        @csrf
        <x-auth-field name="email" label="Email address" type="email" autocomplete="username" required autofocus />
        <x-auth-field name="password" label="Password" type="password" autocomplete="current-password" required />
        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="remember" value="1"> Remember me</label>
        <button class="w-full rounded-full bg-orange-600 py-3 font-semibold text-white hover:bg-orange-700">Log in</button>
    </form>
    <p class="mt-5 text-center text-sm">New customer? <a class="font-semibold text-orange-700 underline" href="{{ route('register') }}">Create an account</a></p>
    <p class="mt-3 text-center text-xs text-neutral-600">Cashier accounts are provided by your administrator.</p>
</x-layouts.guest>
