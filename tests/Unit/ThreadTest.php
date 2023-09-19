<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;

use App\Models\Reply;
use App\Models\Thread;
use App\Notifications\ThreadWasReplied;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ThreadTest extends TestCase
{   // fresh migrations automaticaly migrate:fresh
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
    {   // FAKE() NOTIFICATION use it from memory, not from database
        Notification::fake();
        $this->signIn();
        $this->thread->subscribe();
        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 999
        ]);
        //assertSenTo also reserved method to check if notification is sent to user
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

    public function test_a_thread_can_check_if_auth_is_read_all_replies()
    {
        $this->signIn();

        $user = auth()->user();
        $reply1 = factoryCreate(Reply::class, ['thread_id' => $this->thread->id]);
        $reply2 = factoryCreate(Reply::class, ['thread_id' => $this->thread->id]);

        $this->assertTrue($this->thread->hasUpdatesFor($user));

        $user->read($this->thread);
        
        $this->assertFalse($this->thread->hasUpdatesFor($user));
    }

    function test_a_thread_record_each_visit()
    {
        $thread = factoryMake(Thread::class, ['id' => 1]);
        // trigger record one time
        $thread->visits()->record();
        // // assert 1
        $this->assertEquals(1, $thread->visits()->count());
        // // trigger record second time
        $thread->visits()->record();
        // // assert 2
        $this->assertEquals(2, $thread->visits()->count());
        // Delete the test key from Redis
        $thread->visits()->flush();
        // Assert that the key no longer exists in Redis
        $this->assertEquals(0, $thread->visits()->count());
    }
}
