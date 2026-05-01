<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = ['Toyota', 'BMW', 'Mercedes', 'Lexus', 'Hyundai', 'Kia', 'Nissan', 'Ford'];

        foreach ($brands as $brand) {
            Brand::create(['name' => $brand]);
        }
    }
}
