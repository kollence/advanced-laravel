<?php

namespace Tests\Feature;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Tests\TestCase;

class MentionUserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_mentioned_user_in_a_reply_are_notified()
    {
        //Given we have user JohnDoe who is sign in
        //And another user JaneDoe who is not sign in

        $john = factoryCreate(User::class,['name'=>'JohnDoe']);
        $jane = factoryCreate(User::class,['name'=>'JaneDoe']);
        $this->signIn($john);
        //We have thread
        $thread = factoryCreate(Thread::class);
        //And JohnDoe send a reply and mention JaneDoe and one more user
        $reply = factoryMake(Reply::class,['body'=>'@JaneDoe look at this thread and you too @SecondUser']);
        $this->post($thread->path().'/replies',$reply->toArray());

        //Then, JaneDoe should be notified
        $this->assertCount(1,$jane->notifications);
    }
}
