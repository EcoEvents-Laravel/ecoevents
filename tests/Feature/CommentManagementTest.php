<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_post_a_comment()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();

        $response = $this->actingAs($user)->post(route('comments.store'), [
            'content' => 'Ceci est un commentaire de test très intéressant.',
            'event_id' => $event->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('comments', [
            'content' => 'Ceci est un commentaire de test très intéressant.',
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);
    }

    /** @test */
    public function a_guest_cannot_post_a_comment()
    {
        $event = Event::factory()->create();

        $response = $this->post(route('comments.store'), [
            'content' => 'Un commentaire de fantôme.',
            'event_id' => $event->id,
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseCount('comments', 0);
    }

    /** @test */
    public function a_user_can_delete_their_own_comment()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $user->id]);

        $this->assertDatabaseCount('comments', 1);

        $response = $this->actingAs($user)->delete(route('comments.destroy', $comment));

        $response->assertRedirect();
        $this->assertDatabaseCount('comments', 0);
    }

    /** @test */
    public function a_user_cannot_delete_another_users_comment()
    {
        $commentOwner = User::factory()->create();
        $anotherUser = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $commentOwner->id]);

        $response = $this->actingAs($anotherUser)->delete(route('comments.destroy', $comment));

        $response->assertStatus(403); // Forbidden
        $this->assertDatabaseCount('comments', 1);
    }
}