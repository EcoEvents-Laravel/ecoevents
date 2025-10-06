<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog>
 */
class BlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(6),
            'content' => fake()->paragraphs(5, true),
            'publication_date' => fake()->optional(0.8)->dateTimeThisYear(),
            'status' => fake()->randomElement(['draft', 'published', 'archived']),
            'author_id' => User::inRandomOrder()->first()->id ?? User::factory(),
        ];
    }
}