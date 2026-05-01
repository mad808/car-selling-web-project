<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Car;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create the specific Admin and Client users first
        $this->call([
            UserSeeder::class,
            BrandSeeder::class,
        ]);

        // 2. Create 50 fake cars
        Car::factory(50)->create();
    }
}
