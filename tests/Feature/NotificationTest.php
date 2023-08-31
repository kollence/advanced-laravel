<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_notification_is_prepared_when_subscribed_thred_got_a_new_reply_that_is_not_created_by_current_subscribed_user()
    {
                //sign in
                $this->signIn();
                //create thread
                $thread = factoryCreate(\App\Models\Thread::class)->subscribe();
                // check that subscribed thread doesn't have a notification
                $this->assertCount(0, auth()->user()->notifications);
                // reply created by me
                $thread->addReply([
                    'user_id' => auth()->id(),
                    'body' => 'My reply will not notify me'
                ]);
                // make sure that notification is not created for current subscribed user
                $this->assertCount(0, auth()->user()->fresh()->notifications);
                // reply created from other user
                $thread->addReply([
                    'user_id' => factoryCreate(\App\Models\User::class)->id,
                    'body' => 'Others users reply will notify me'
                ]);
                // reply from others will notify me
                $this->assertCount(1, auth()->user()->fresh()->notifications);

    }
}
