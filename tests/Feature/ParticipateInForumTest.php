<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_an_authenticated_user_may_participate_in_forum_threads()
    {
        // Given we have an authenticated user
        $this->be($user = \App\Models\User::factory()->create());

        // And an existing thread
        $thread = \App\Models\Thread::factory()->create();

        // When the user adds a reply to the thread
        $reply = \App\Models\Reply::factory()->make();
        $this->post('/threads/' . $thread->id . '/replies', $reply->toArray());

        // Then their reply should be visible on the page
        $this->get('/threads/' . $thread->id)
            ->assertSee($reply->body);
    }
}
