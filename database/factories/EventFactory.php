<?php

namespace Database\Factories;

use App\Models\Event; // <-- LA LIGNE LA PLUS IMPORTANTE !
use App\Models\EventType;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(3),
            'start_date' => $this->faker->dateTimeBetween('+1 week', '+2 week'),
            'end_date' => $this->faker->dateTimeBetween('+2 week', '+3 week'),
            'city' => $this->faker->city,
            'country' => $this->faker->country,
            'address' => $this->faker->address,
            'max_participants' => $this->faker->numberBetween(50, 200),
            'event_type_id' => EventType::factory(),
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Event $event) { // <-- LE TYPE EST MAINTENANT CORRECT
            // Attache un ou plusieurs tags à l'événement créé
            if (Tag::count() > 0) {
                $tags = Tag::inRandomOrder()->limit(rand(1, 3))->pluck('id');
                $event->tags()->attach($tags);
            }
        });
    }
}