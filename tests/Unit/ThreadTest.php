<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ThreadTest extends TestCase
{
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

        $this->thread = \App\Models\Thread::factory()->create();
    }

    public function test_thread_has_reply()
    {
        $thread = \App\Models\Thread::factory()->create();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $thread->replies);
    }

    public function test_thread_has_creator()
    {
        $thread = \App\Models\Thread::factory()->create();

        $this->assertInstanceOf('App\Models\User', $thread->user);
    }

    public function test_thread_can_add_reply()
    {
        $thread = \App\Models\Thread::factory()->create();

        $thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);

        $this->assertCount(1, $thread->replies);
    }
}
