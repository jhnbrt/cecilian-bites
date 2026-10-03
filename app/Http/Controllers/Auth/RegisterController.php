<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function create(Request $request): View
    {
        return view('auth.register', [
            'role' => UserRole::tryFrom((string) $request->query('role')) ?? UserRole::Cashier,
        ]);
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => $request->validated('password'),
            'role' => $request->enum('role', UserRole::class),
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->route($user->role->homeRoute());
    }
}
