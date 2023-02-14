<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'isbn' => fake()->numberBetween(1000000000000, 9999999999999),
            'title' => fake()->word(),
            'description' => fake()->words(10, true),
            'authors' => [fake()->name()],
            'publisher' => fake()->name(),
            'published_date' => fake()->date(),
            'page_count' => fake()->randomNumber(2),
            'price' => fake()->numberBetween(100000, 999999),
            'thumbnail' => 'test',
            'category_id' => fake()->numberBetween(1, 9),
        ];
    }
}
