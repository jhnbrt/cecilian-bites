<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Both demo accounts use the password "password".
     */
    public function run(): void
    {
        User::query()->firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin', 'password' => 'password', 'role' => UserRole::Admin],
        );

        User::query()->firstOrCreate(
            ['email' => 'cashier@example.com'],
            ['name' => 'Cashier', 'password' => 'password', 'role' => UserRole::Cashier],
        );

        $this->call([
            MenuSeeder::class,
            DiningTableSeeder::class,
            OrderSeeder::class,
        ]);
    }
}
