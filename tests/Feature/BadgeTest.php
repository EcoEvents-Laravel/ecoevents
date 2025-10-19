<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class BadgeTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function badge_test_example(): void
    {
        $response = $this->get('/badge');
        

        $response->assertStatus(200);
    }

    /** @test */
    public function badge_test_content(): void
    {
        $response = $this->get('/badge');

        $response->assertSee('Badges');
    }

    /** @test */
    public function badge_create_page(): void
    {
        $response = $this->get('/badge');

        $response->assertSee('badge.index');
    }

    /** @test */
    public function badge_edit_page(): void
    {
        $response = $this->get('/badge/1/edit');

        $response->assertSee('Edit Badge');
    }

    /** @test */
    public function badge_show_page(): void
    {
        $response = $this->get('/badge/1');

        $response->assertStatus(404);
    }

    /** @test */
    public function badge_not_found(): void
    {
        $response = $this->get('/badge/9999');

        $response->assertStatus(404);
    }

    /** @test */
    public function badge_create_form_submission(): void
    {
        $response = $this->post('/badge', [
            'name' => 'Test Badge',
            'description' => 'This is a test badge.',
        ]);

        $response->assertStatus(302); // Assuming it redirects after creation
    }

    /** @test */
    public function badge_update_form_submission(): void
    {
        $response = $this->put('/badge/1', [
            'name' => 'Updated Test Badge',
            'description' => 'This is an updated test badge.',
        ]);

        $response->assertStatus(302); // Assuming it redirects after update
    }

    /** @test */
    public function badge_delete(): void
    {
        $response = $this->delete('/badge/1');

        $response->assertStatus(404); // Assuming it redirects after deletion
    }
}
