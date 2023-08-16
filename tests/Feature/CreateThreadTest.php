<?php

namespace Tests\Feature;

use App\Models\Channel;
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
        $response = $this->post('/threads', $thread->toArray());
        //redirect to thread page taking from ThreadController redirect
        $redirect = $response->headers->get('location');
        $this->get($redirect)
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    function test_a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])->assertSessionHasErrors('title');
    }

    function test_a_thread_requires_a_body()
    {

        $this->publishThread(['body' => null])->assertSessionHasErrors('body');
    }

    function test_a_thread_requires_a_valid_channel()
    {
        $cannels = \App\Models\Channel::factory(2)->create();
        $this->publishThread(['channel_id' => null])->assertSessionHasErrors('channel_id');
        $this->publishThread(['channel_id' => 99999])->assertSessionHasErrors('channel_id');
    }

    public function publishThread($overrides = [])
    {
        $this->signIn();

        $thread = factoryMake(\App\Models\Thread::class, $overrides);

        return $this->post('/threads', $thread->toArray());
            
    }
}
