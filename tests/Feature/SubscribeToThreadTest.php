<?php

namespace Tests\Feature;


use Tests\TestCase;

class SubscribeToThreadTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_auth_can_subscribe_to_a_thread()
    {
        //sign in
        $this->signIn();
        //create thread
        $thread = factoryCreate(\App\Models\Thread::class);
        // subscribe to thread
        $this->post($thread->path() . '/subscriptions');
        // check if user is subsribed 
        $this->assertCount(1, $thread->subscriptions);
    }
}
