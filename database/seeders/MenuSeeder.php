<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MenuSeeder extends Seeder
{
    /**
     * Seed the menu categories and dishes.
     */
    public function run(): void
    {
        $menu = [
            'Main Course' => [
                'BBQ Chicken' => 10.00,
                'Tuna Boiled Eggs' => 12.50,
                'Meat Sauce Soup' => 18.00,
                'Chicken Slices' => 22.00,
                'Arabic Kebab' => 28.00,
                'Chicken Leg' => 12.00,
                'Bacon Slices' => 13.00,
                'Grilled Chicken' => 10.00,
                'Fried Rice Pasta' => 16.50,
                'Grilled Pork' => 15.00,
                'Pasta with Chicken' => 7.50,
                'Dumplings Peas' => 20.00,
            ],
            'Soup' => [
                'Tomato Basil Soup' => 6.50,
                'Cream of Mushroom' => 7.00,
                'Chicken Noodle Soup' => 7.50,
            ],
            'Salads' => [
                'Caesar Salad' => 9.00,
                'Greek Salad' => 9.50,
                'Garden Salad' => 8.00,
            ],
            'Drinks' => [
                'Iced Tea' => 3.00,
                'Fresh Lemonade' => 3.50,
                'Mango Shake' => 5.00,
                'Brewed Coffee' => 3.00,
            ],
            'Appetizer' => [
                'Spring Rolls' => 6.00,
                'Garlic Bread' => 5.00,
                'Loaded Nachos' => 8.00,
            ],
            'Dessert' => [
                'Chocolate Cake' => 6.50,
                'Leche Flan' => 5.50,
                'Ice Cream Sundae' => 5.00,
            ],
        ];

        $sortOrder = 0;

        foreach ($menu as $categoryName => $items) {
            $category = Category::query()->firstOrCreate(
                ['slug' => Str::slug($categoryName)],
                ['name' => $categoryName, 'sort_order' => $sortOrder++],
            );

            foreach ($items as $name => $price) {
                MenuItem::query()->firstOrCreate(
                    ['category_id' => $category->id, 'name' => $name],
                    ['price' => $price],
                );
            }
        }
    }
}
