<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => fake()->uuid(),
            'name' => fake()->sentence(3),
            'price' => fake()->randomFloat(2),
            'brand' => fake()->word(),
            'category' => fake()->word(),
            'description' => fake()->paragraphs(4, true),
            'image_url' => fake()->imageUrl(),
            'is_popular' => fake()->numberBetween(0, 1),
            'quantity' => fake()->randomNumber(4),
            'sales' => fake()->randomNumber(4),
            'user_id' => fake()->randomElement(User::pluck('id')),
        ];
    }
}
