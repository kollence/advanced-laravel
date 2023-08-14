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

        $this->thread = \App\Models\Thread::factory()->create();
    }

    public function test_user_can_browse_threads()
    {
        $this->get('/threads')
            ->assertSee($this->thread->title);
    }

    public function test_user_can_read_a_single_thread()
    {
        $this->get('/threads/' . $this->thread->id)
            ->assertSee($this->thread->title);
    }

    public function test_user_can_read_replies_that_are_associated_with_a_thread()
    {
        $reply = \App\Models\Reply::factory()->create(['thread_id' => $this->thread->id]);

        $response = $this->get('/threads/' . $this->thread->id);
        $response->assertSee($reply->body);
    }
}
