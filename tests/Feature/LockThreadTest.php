<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LockThreadTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_non_admin_cannot_lock_thread()
    {
        $this->signIn();

        $thread = factoryCreate(\App\Models\Thread::class, ['user_id' => auth()->id(), 'locked' => false]);

        $this->post($thread->path() . '/lock')->assertStatus(403);

        $this->assertFalse((bool) $thread->fresh()->locked);
    }

    public function test_admin_can_lock_any_thread()
    {
        $this->signInAsAdmin();
        $thread = factoryCreate(\App\Models\Thread::class, ['user_id' => auth()->id(),'locked' => false]);

        $this->post($thread->path() . '/lock');

        $this->assertTrue((bool) $thread->fresh()->locked);
    }

    public function test_once_locked_thread_cannot_receive_new_replies()
    {
        $this->signInAsAdmin();
        $thread = factoryCreate(\App\Models\Thread::class, ['user_id' => auth()->id(),'locked' => false]);
        $this->post($thread->path() . '/lock');
        $this->assertTrue((bool) $thread->fresh()->locked);

        $reply = factoryCreate(\App\Models\Reply::class, ['thread_id' => $thread->id]);

        $this->post($thread->path() . '/replies', $reply->toArray())->assertStatus(422);
    }

    public function test_non_admin_cannot_unlock_thread()
    {
        $this->signInAsAdmin();
        
        $thread = factoryCreate(\App\Models\Thread::class, ['user_id' => auth()->id(),'locked' => false]);

        $this->post($thread->path() . '/lock');

        $this->assertTrue((bool) $thread->fresh()->locked);

        $this->signIn();

        $this->post($thread->path() . '/unlock')->assertStatus(403);

        $this->assertTrue((bool) $thread->fresh()->locked);
    }

    public function test_admin_can_unlock_any_thread()
    {
        $this->signInAsAdmin();

        $thread = factoryCreate(\App\Models\Thread::class, ['user_id' => auth()->id(), 'locked' => true]);

        $this->post($thread->path() . '/unlock');

        $this->assertFalse((bool) $thread->fresh()->locked);
    }
}
