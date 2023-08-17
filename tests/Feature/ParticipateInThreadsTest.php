<?php

namespace Tests\Feature;

use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ParticipateInThreadsTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_unauthenticated_user_cant_add_reply()
    {
        $this->post('/threads/some-channel/1/replies', [])->assertRedirect('/login');
    }

    public function test_authenticated_user_can_add_reply()
    { 
        $this->signIn();

        $thread = factoryCreate(Thread::class);
        $reply = factoryMake(Reply::class);
        $this->post($thread->path().'/replies', $reply->toArray());
        $this->get($thread->path())->assertSee($reply->body);
    }

    public function test_a_reply_requiers_a_body()
    {
        // WONT WORK ERROR ON assertSessionHasErrors
        $this->signIn();
        $thread = factoryCreate(Thread::class);
        $reply = factoryMake(Reply::class, ['body' => null]);
        $this->post($thread->path().'/replies', $reply->toArray())
        ->assertSessionHasErrors('body');
        
    }
}
