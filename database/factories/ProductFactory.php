<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'price' => fake()->numberBetween(50, 1000) * 1000,
            'stock' => fake()->numberBetween(10, 200),
        ];
    }
}
