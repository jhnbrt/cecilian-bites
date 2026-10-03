<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Enums\UserRole;
use App\Models\DiningTable;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Seed a handful of demo orders so the order line is not empty.
     */
    public function run(): void
    {
        $user = User::query()->where('role', UserRole::Cashier->value)->first();
        $tables = DiningTable::query()->orderBy('id')->limit(4)->get();

        $statuses = [
            OrderStatus::ReadyToServe,
            OrderStatus::InProgress,
            OrderStatus::Canceled,
            OrderStatus::Completed,
        ];

        foreach ($tables as $index => $table) {
            Order::factory()
                ->status($statuses[$index])
                ->withItems()
                ->create([
                    'user_id' => $user?->id,
                    'dining_table_id' => $table->id,
                ]);
        }

        Order::factory()->takeAway()->withItems(2)->create(['user_id' => $user?->id]);
        Order::factory()->delivery()->withItems(4)->create(['user_id' => $user?->id]);
    }
}
