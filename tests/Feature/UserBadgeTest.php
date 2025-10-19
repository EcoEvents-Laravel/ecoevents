<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserBadgeTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function user_badge_test_example(): void
    {
        $response = $this->get('/user_badge');

        $response->assertStatus(200);
    }

    /** @test */
    public function user_badge_test_content(): void
    {
        $response = $this->get('/user_badge');

        $response->assertSee('User Badges');
    }

    /** @test */
    public function user_badge_not_found(): void
    {
        $response = $this->get('/user_badge/9999');

        $response->assertStatus(404);
    }

    /** @test */
    public function user_badge_create_form_submission(): void
    {
        $response = $this->post('/user_badge', [
            'user_id' => 1,
            'badge_id' => 1,
            'awarded_at' => '2024-01-01',
        ]);

        $response->assertStatus(302); // Assuming a redirect after successful creation
    }

    /** @test */
    public function user_badge_update_form_submission(): void
    {
        $response = $this->put('/user_badge/1', [
            'user_id' => 1,
            'badge_id' => 2,
            'awarded_at' => '2024-02-01',
        ]);

        $response->assertStatus(302); // Assuming a redirect after successful update
    }

    /** @test */
    public function user_badge_delete(): void
    {
        $response = $this->delete('/user_badge/1');

        $response->assertStatus(404); // Assuming a redirect after successful deletion
    }
}
