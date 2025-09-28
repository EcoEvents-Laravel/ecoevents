<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_register_for_an_event()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();

        $response = $this->actingAs($user)->post(route('registrations.store'), [
            'event_id' => $event->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('registrations', [
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);
    }

    /** @test */
    public function a_user_cannot_register_for_the_same_event_twice()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        
        // Première inscription
        Registration::factory()->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);

        $this->assertDatabaseCount('registrations', 1);

        // Tentative de deuxième inscription
        $response = $this->actingAs($user)->post(route('registrations.store'), [
            'event_id' => $event->id,
        ]);
        
        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('registrations', 1);
    }

    /** @test */
    public function a_user_can_cancel_their_registration()
    {
        $user = User::factory()->create();
        $registration = Registration::factory()->create(['user_id' => $user->id]);

        $this->assertDatabaseCount('registrations', 1);

        $response = $this->actingAs($user)->delete(route('registrations.destroy', $registration));

        $response->assertRedirect();
        $this->assertDatabaseCount('registrations', 0);
    }
}