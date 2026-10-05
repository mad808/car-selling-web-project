<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Brand;

class CarFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(['approved', 'approved', 'approved', 'pending', 'rejected']);

        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'brand_id' => Brand::inRandomOrder()->first()->id ?? Brand::factory(),
            'title' => fake()->sentence(3),
            'model' => ucfirst(fake()->word()), 
            'year' => fake()->numberBetween(2008, 2025),
            'price' => fake()->randomFloat(2, 6000, 85000),
            
            'mileage' => fake()->numberBetween(10000, 220000),
            'fuel_type' => fake()->randomElement(['Petrol', 'Diesel', 'Hybrid', 'Electric']),
            'transmission' => fake()->randomElement(['Automatic', 'Manual']),
            
            'description' => fake()->paragraph(),
            'image' => 'cars/' . $this->faker->numberBetween(1, 30) . '.png',
            'is_sold' => fake()->boolean(15),

            'status' => $status,
            'admin_note' => $status === 'rejected' ? 'Suratlary talaba laýyk däl ýa-da baha dogry bellenilmedik.' : null,

            'ai_verdict' => fake()->randomElement(['GREAT DEAL', 'FAIR PRICE', 'OVERPRICED']),
            'ai_review' => fake()->randomElement([
                'Lokal AI Derňewi: Ýylyna we probegine görä bahasy örän amatly. Tehniki ýagdaýy kadaly.',
                'Lokal AI Derňewi: Ýyllyk ýörän ýoly biraz ýokary, emma umumy baha bazara laýyk gelýär.',
                'Lokal AI Derňewi: Bahasy bazardaky ortaça bahadan ýokaryrak bolup biler, söwda etmäge mümkinçilik bar.'
            ]),
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'admin_note' => null,
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'admin_note' => null,
        ]);
    }
}