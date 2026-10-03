<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->actingAs($this->admin);
});

test('the staff page lists every account with its role', function () {
    User::factory()->cashier()->create(['name' => 'Casey Cashier']);

    $this->get(route('admin.staff.index'))
        ->assertOk()
        ->assertSee($this->admin->name)
        ->assertSee('Casey Cashier')
        ->assertSee('Cashier');
});

test('an admin can create a cashier and the cashier can log in', function () {
    $this->post(route('admin.staff.store'), [
        'name' => 'New Cashier',
        'email' => 'new@example.com',
        'role' => 'cashier',
        'password' => 'secret-pass-123',
        'password_confirmation' => 'secret-pass-123',
    ])->assertRedirect(route('admin.staff.index'));

    $user = User::query()->where('email', 'new@example.com')->sole();
    expect($user->role)->toBe(UserRole::Cashier);

    $this->post(route('logout'));

    $this->post(route('login'), [
        'role' => 'cashier',
        'email' => 'new@example.com',
        'password' => 'secret-pass-123',
    ])->assertRedirect(route('dashboard'));
});

test('a new account needs a password and a unique email', function () {
    $this->post(route('admin.staff.store'), [
        'name' => 'No Password',
        'email' => $this->admin->email,
        'role' => 'cashier',
    ])->assertSessionHasErrors(['email', 'password']);
});

test('an admin can change a role and reset a password', function () {
    $cashier = User::factory()->cashier()->create();

    $this->put(route('admin.staff.update', $cashier), [
        'name' => 'Promoted',
        'email' => $cashier->email,
        'role' => 'admin',
        'password' => 'brand-new-pass-1',
        'password_confirmation' => 'brand-new-pass-1',
    ])->assertRedirect(route('admin.staff.index'));

    $cashier->refresh();

    expect($cashier->name)->toBe('Promoted')
        ->and($cashier->role)->toBe(UserRole::Admin)
        ->and(Hash::check('brand-new-pass-1', $cashier->password))->toBeTrue();
});

test('leaving the password empty keeps the current one', function () {
    $cashier = User::factory()->cashier()->create();
    $before = $cashier->password;

    $this->put(route('admin.staff.update', $cashier), [
        'name' => 'Renamed',
        'email' => $cashier->email,
        'role' => 'cashier',
        'password' => '',
        'password_confirmation' => '',
    ])->assertRedirect(route('admin.staff.index'));

    expect($cashier->refresh()->password)->toBe($before);
});

test('the only admin cannot be demoted or deleted', function () {
    $this->put(route('admin.staff.update', $this->admin), [
        'name' => $this->admin->name,
        'email' => $this->admin->email,
        'role' => 'cashier',
    ])->assertSessionHasErrors('role');

    expect($this->admin->refresh()->role)->toBe(UserRole::Admin);

    $this->delete(route('admin.staff.destroy', $this->admin))->assertSessionHasErrors('staff');
    $this->assertModelExists($this->admin);
});

test('an admin cannot delete their own account but can delete others', function () {
    $other = User::factory()->admin()->create();
    $cashier = User::factory()->cashier()->create();

    $this->delete(route('admin.staff.destroy', $this->admin))->assertSessionHasErrors('staff');
    $this->assertModelExists($this->admin);

    $this->delete(route('admin.staff.destroy', $cashier))->assertRedirect();
    $this->assertModelMissing($cashier);

    $this->delete(route('admin.staff.destroy', $other))->assertRedirect();
    $this->assertModelMissing($other);
});
