<?php

namespace Database\Seeders;

use App\Models\EventType;
use App\Models\Tag;
use App\Models\Event;
use Illuminate\Database\Seeder;
use Database\Factories\EventFactory;
use Database\Factories\EventTypeFactory;
use Database\Factories\TagFactory;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // Seed EventTypes
        $types = collect([
            EventType::factory()->create(['name' => 'Conference', 'color' => '#FF0000']),
            EventType::factory()->create(['name' => 'Workshop', 'color' => '#00FF00']),
            EventType::factory()->create(['name' => 'Seminar', 'color' => '#0000FF']),
        ]);

        // Seed Tags
        $tags = collect([
            Tag::factory()->create(['name' => 'Tech']),
            Tag::factory()->create(['name' => 'AI']),
            Tag::factory()->create(['name' => 'Innovation']),
            Tag::factory()->create(['name' => 'Education']),
            Tag::factory()->create(['name' => 'Networking']),
        ]);

        // Seed Events with random associations
        Event::factory()->count(10)->create()->each(function ($event) use ($types, $tags) {
            $event->event_type_id = $types->random()->id;
            $event->save();
            $event->tags()->attach($tags->random(rand(1, 3))->pluck('id'));
        });
    }
}