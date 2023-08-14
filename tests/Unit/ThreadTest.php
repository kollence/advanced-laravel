<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ThreadTest extends TestCase
{   // fresh migrations automaticaly migrate:fresh
    use DatabaseMigrations;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    protected $thread;

    public function setUp() : void
    {
        parent::setUp();    
        //initialize one $thread as ready & created with factory
        $this->thread = \App\Models\Thread::factory()->create();
    }

    public function test_thread_has_reply()
    {
        // replies are assertInstanceOf thread
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    public function test_thread_has_creator()
    {
        // user are assertInstanceOf thread
        $this->assertInstanceOf('App\Models\User', $this->thread->user);
    }

    public function test_thread_can_add_reply()
    {
        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);

        $this->assertCount(1, $this->thread->replies);
    }
}
