<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EventTypeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'color' => $this->faker->hexColor(),
            'icon' => $this->faker->word(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}