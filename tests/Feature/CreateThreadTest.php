<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CreateThreadTest extends TestCase
{
    use DatabaseMigrations;

    function test_guest_cant_create_thread()
    {   // this 2 lines are needed for successful passing the test for Unauthenticated can't create thread
        $this->withoutExceptionHandling();
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $thread = factoryMake(\App\Models\Thread::class);
        // If this throws a 500 error, it’ll now display in the console instead
        // This won’t be called, as the exception above will halt execution
        $this->post(route('threads.store'), $thread->toArray());  
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    function test_auth_user_can_create_thread()
    {
        //authenticate user
        $this->signIn();
        //create thread
        $thread = factoryMake(\App\Models\Thread::class);
        $this->post('/threads', $thread->toArray());
        //redirect to thread page
        $this->get('/threads')
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
