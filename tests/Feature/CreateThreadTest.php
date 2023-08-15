<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CreateThreadTest extends TestCase
{
    use DatabaseMigrations;

    function test_guest_cant_create_thread()
    {   // with Exception Handling
        $this->withExceptionHandling();
        // can't go to page
        $this->get('/threads/create')
        ->assertRedirect('/login');

        $thread = factoryMake(\App\Models\Thread::class);
        // can't create
        $this->post(route('threads.store'), $thread->toArray())
        ->assertRedirect('/login');  
    }

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

    function test_a_thread_requires_a_title()
    {

        $this->publishThread(['title' => null])->assertSessionHasErrors('title');
    }

    public function publishThread($overrides = [])
    {
        $this->signIn();

        $thread = factoryMake(\App\Models\Thread::class, $overrides);

        return $this->post('/threads', $thread->toArray());
            
    }
}
