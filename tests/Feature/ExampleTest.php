<?php

use App\Models\User;

test('guests at the root url are sent to the login page', function () {
    $this->get('/')->assertRedirect(route('login'));
});

test('the root url sends each role to its own dashboard', function () {
    $this->actingAs(User::factory()->cashier()->create())
        ->get('/')
        ->assertRedirect(route('dashboard'));

    $this->actingAs(User::factory()->admin()->create())
        ->get('/')
        ->assertRedirect(route('admin.dashboard'));
});
