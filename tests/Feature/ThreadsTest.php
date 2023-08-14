<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ThreadsTest extends TestCase
{
    protected $thread;
    use DatabaseMigrations;
    /**
     * A basic test example.
     *
     * @return void
     */

    public function setUp() : void
    {
        //initialize one $thread as ready & created with factory
        parent::setUp();    

        $this->thread = factoryCreate(\App\Models\Thread::class);
    }

    public function test_guest_can_browse_threads()
    {
        // go to route & see if created thread can be seen on page /threads
        $this->get('/threads')
            ->assertSee($this->thread->title);
    }

    public function test_guest_can_read_a_single_thread()
    {
        // go to route & see if created single thread can be seen on page /threads/{id}
        $this->get('/threads/' . $this->thread->id)
            ->assertSee($this->thread->title);
    }

    public function test_guest_can_read_replies_that_are_associated_with_a_thread()
    {
        $formData = ['thread_id' => $this->thread->id];
        //create reply
        $reply = factoryCreate(\App\Models\Reply::class, $formData);
        // go to route & see if created reply can be seen on page /threads/{id}
        $response = $this->get('/threads/' . $this->thread->id);
        $response->assertSee($reply->body);
    }
}
