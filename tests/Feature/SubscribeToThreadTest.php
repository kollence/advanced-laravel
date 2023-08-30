<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubscribeToThreadTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_auth_can_subscribe_to_a_thread()
    {
        $this->signIn();
        $thread = factoryCreate(\App\Models\Thread::class);

        $this->post($thread->path() . '/subscriptions');

        // $thread->addReply([
        //     'user_id' => auth()->id(),
        //     'body' => 'Foobar'
        // ]);
        $this->assertCount(1, $thread->subscriptions);
    }
}
