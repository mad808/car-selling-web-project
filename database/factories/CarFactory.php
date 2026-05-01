<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Brand; // Import Brand

class CarFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'brand_id' => Brand::inRandomOrder()->first()->id ?? Brand::factory(), // <--- Pick a random brand
            'title' => fake()->sentence(3),
            'model' => fake()->word(), 
            'year' => fake()->numberBetween(2005, 2025),
            'price' => fake()->randomFloat(2, 5000, 100000),
            'description' => fake()->paragraph(),
            'image' => 'cars/' . $this->faker->numberBetween(1, 30) . '.png',
            'is_sold' => fake()->boolean(10),
        ];
    }
}
