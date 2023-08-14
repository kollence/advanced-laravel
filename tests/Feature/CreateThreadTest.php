<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CreateThreadTest extends TestCase
{
    use DatabaseMigrations;

    function test_guest_cant_create_thread()
    {
        $this->withoutExceptionHandling();
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $thread = \App\Models\Thread::factory()->make();
        $this->post(route('threads.store'), $thread->toArray());  
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    function test_auth_user_can_create_thread()
    {
        //sign in user
        $this->actingAs(\App\Models\User::factory()->create());

        //create thread
        $thread = \App\Models\Thread::factory()->make();
        $this->post('/threads', $thread->toArray());

        //redirect to thread page
        $this->get('/threads')
            ->assertSee($thread->title)
            ->assertSee($thread->body);


    }
}
