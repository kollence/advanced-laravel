<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;




    protected $thread;
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
        $this->get($this->thread->path())
            ->assertSee($this->thread->title);
    }

    public function test_guest_can_read_replies_that_are_associated_with_a_thread()
    {
        $formData = ['thread_id' => $this->thread->id];
        //create reply
        $reply = factoryCreate(\App\Models\Reply::class, $formData);
        // go to route & see if created reply can be seen on page /threads/{id}
        $response = $this->get($this->thread->path());
        $response->assertSee($reply->body);
    }

    public function test_user_can_filter_threads_according_to_a_tag()
    {
    // Create a channel and threads associated with it
    $channel = factoryCreate(\App\Models\Channel::class);
    $threadInChannel = factoryCreate(\App\Models\Thread::class, ['channel_id' => $channel->id]);
    $threadNotInChannel = factoryCreate(\App\Models\Thread::class);
    // Access the URL corresponding to the channel's slug
    $response = $this->get(route('threads.index', ['channel' => $channel->slug]))
        ->assertSee($threadInChannel->title) // Check if the thread is visible
        ->assertDontSee($threadNotInChannel->title); // Check if the other thread is not visible

    }
}
