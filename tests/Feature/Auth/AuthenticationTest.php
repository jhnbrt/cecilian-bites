<?php

beforeEach(function () { config(['restaurant.public_staff_registration' => true]); });

use App\Enums\UserRole;
use App\Models\User;

test('guests are redirected to the login page', function () {
    $this->get(route('dashboard'))->assertRedirect(route('login'));
    $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
});

test('the login and register screens render', function () {
    $this->get(route('login'))->assertOk()->assertSee('Welcome back')->assertSee('Cashier')->assertSee('Admin');
    $this->get(route('register'))->assertOk()->assertSee('Create your account')->assertSee('Admin access code');
});

test('cashiers log in and land on the cashier dashboard', function () {
    $user = User::factory()->cashier()->create();

    $this->post(route('login'), [
        'role' => 'cashier',
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});

test('admins log in and land on the admin dashboard', function () {
    $user = User::factory()->admin()->create();

    $this->post(route('login'), [
        'role' => 'admin',
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('admin.dashboard'));

    $this->assertAuthenticatedAs($user);
});

test('users cannot log in under a role that is not theirs', function () {
    $cashier = User::factory()->cashier()->create();

    $this->from(route('login'))->post(route('login'), [
        'role' => 'admin',
        'email' => $cashier->email,
        'password' => 'password',
    ])->assertRedirect(route('login'))->assertSessionHasErrors('email');

    $this->assertGuest();
});

test('users cannot log in with a wrong password', function () {
    $user = User::factory()->create();

    $response = $this->from(route('login'))->post(route('login'), [
        'role' => 'cashier',
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertRedirect(route('login'));
    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('logging in requires choosing a role', function () {
    $user = User::factory()->create();

    $this->post(route('login'), ['email' => $user->email, 'password' => 'password'])
        ->assertSessionHasErrors('role');

    $this->assertGuest();
});

test('anyone can register as a cashier', function () {
    $response = $this->post(route('register'), [
        'role' => 'cashier',
        'name' => 'Maria Cruz',
        'email' => 'maria@example.com',
        'password' => 'secret-pass-123',
        'password_confirmation' => 'secret-pass-123',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', ['email' => 'maria@example.com', 'name' => 'Maria Cruz', 'role' => 'cashier']);
});

test('registering as an admin needs the admin access code', function () {
    config(['restaurant.admin_registration_code' => 'let-me-in']);

    $payload = [
        'role' => 'admin',
        'name' => 'Owner Olivia',
        'email' => 'olivia@example.com',
        'password' => 'secret-pass-123',
        'password_confirmation' => 'secret-pass-123',
    ];

    $this->post(route('register'), $payload)->assertSessionHasErrors('admin_code');
    $this->post(route('register'), $payload + ['admin_code' => 'wrong'])->assertSessionHasErrors('admin_code');
    $this->assertDatabaseMissing('users', ['email' => 'olivia@example.com']);

    $this->post(route('register'), $payload + ['admin_code' => 'let-me-in'])
        ->assertRedirect(route('admin.dashboard'));

    expect(User::query()->where('email', 'olivia@example.com')->sole()->role)->toBe(UserRole::Admin);
});

test('admin registration is off when no access code is configured', function () {
    config(['restaurant.admin_registration_code' => null]);

    $this->post(route('register'), [
        'role' => 'admin',
        'name' => 'Sneaky',
        'email' => 'sneaky@example.com',
        'password' => 'secret-pass-123',
        'password_confirmation' => 'secret-pass-123',
        'admin_code' => 'anything',
    ])->assertSessionHasErrors('admin_code');

    $this->assertDatabaseMissing('users', ['email' => 'sneaky@example.com']);
});

test('registration requires a matching password confirmation and a unique email', function () {
    $existing = User::factory()->create();

    $response = $this->from(route('register'))->post(route('register'), [
        'role' => 'cashier',
        'name' => 'Maria Cruz',
        'email' => $existing->email,
        'password' => 'secret-pass-123',
        'password_confirmation' => 'something-else',
    ]);

    $response->assertSessionHasErrors(['email', 'password']);
    $this->assertGuest();
});

test('authenticated users are redirected away from the login screen', function () {
    $this->actingAs(User::factory()->cashier()->create())
        ->get(route('login'))
        ->assertRedirect(route('dashboard'));

    $this->actingAs(User::factory()->admin()->create())
        ->get(route('login'))
        ->assertRedirect(route('admin.dashboard'));
});

test('users can log out', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('logout'))
        ->assertRedirect(route('login'));

    $this->assertGuest();
});
