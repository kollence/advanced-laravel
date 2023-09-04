<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;

use App\Notifications\ThreadWasReplied;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Notification;
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
        $this->thread = factoryCreate(\App\Models\Thread::class);
    }
    
    public function test_thread_can_make_string_path()
    {
       $this->assertEquals("/threads/{$this->thread->channel->slug}/{$this->thread->id}", $this->thread->path());
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

    public function test_thread_can_have_added_reply()
    {
        // addReplay method created in Thread model to create thred by passing array
        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);
        // check if thread have 1 reply created
        $this->assertCount(1, $this->thread->replies);
    }

    public function test_a_thread_notify_all_registered_subscribers_when_reply_is_added()
    {
        Notification::fake();
        $this->signIn();
        $this->thread->subscribe();
        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);
        Notification::assertSentTo(auth()->user(), ThreadWasReplied::class);
    }

    function test_thread_belongs_to_channel()
    {
        $this->assertInstanceOf('App\Models\Channel', $this->thread->channel);
    }

    function test_thread_can_be_subscribed()
    {
        $this->thread->subscribe($userId = 1);

        $this->assertEquals(1, $this->thread->subscriptions()->where('user_id', $userId)->count());
    }

    function test_thread_can_be_unsubscribed()
    {

        $this->thread->subscribe($userId = 1);

        $this->thread->unsubscribe($userId);

        $this->assertEquals(0, $this->thread->subscriptions()->where('user_id', $userId)->count());
    }
}
