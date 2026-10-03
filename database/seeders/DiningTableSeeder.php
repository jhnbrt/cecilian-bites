<?php

namespace Database\Seeders;

use App\Models\DiningTable;
use Illuminate\Database\Seeder;

class DiningTableSeeder extends Seeder
{
    /**
     * Seed the restaurant floor plan.
     */
    public function run(): void
    {
        foreach (range(1, 12) as $number) {
            DiningTable::query()->firstOrCreate(
                ['name' => 'Table '.$number],
                ['seats' => $number <= 6 ? 4 : 6],
            );
        }
    }
}
