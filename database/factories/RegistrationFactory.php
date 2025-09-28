<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegistrationFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'event_id' => Event::factory(),
            'status' => 'confirmed',
        ];
    }
}