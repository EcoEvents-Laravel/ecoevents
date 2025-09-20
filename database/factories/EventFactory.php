<?php

namespace Database\Factories;

use App\Models\EventType;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'start_date' => now()->addDays(rand(1, 30)),
            'end_date' => now()->addDays(rand(31, 60)),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'postal_code' => $this->faker->postcode(),
            'country' => $this->faker->country(),
            'status' => $this->faker->randomElement(['draft', 'published', 'cancelled']),
            'event_type_id' => EventType::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Event $event) {
            $event->tags()->attach(Tag::factory()->count(rand(1, 3))->create()->pluck('id'));
        });
    }
}