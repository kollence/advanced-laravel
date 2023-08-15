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
        // $this->be( $user = factoryCreate(\App\Models\User::class) );
        $this->signIn();
        // And an existing thread
        $thread = factoryCreate(\App\Models\Thread::class);
        // When the user adds a reply to the thread
        $reply = factoryCreate(\App\Models\Reply::class);
        $this->post('/threads/'.$thread->channel->slug.'/'. $thread->id . '/replies', $reply->toArray());
        // Then their reply should be visible on the page
        $this->get($thread->path())
            ->assertSee($reply->body);
    }
}
