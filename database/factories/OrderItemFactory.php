<?php

namespace Database\Factories;

use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'menu_item_id' => MenuItem::factory(),
            'name' => Str::title(fake()->words(2, true)),
            'price' => fake()->randomFloat(2, 3, 30),
            'quantity' => fake()->numberBetween(1, 3),
        ];
    }
}
