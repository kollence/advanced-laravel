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

        $thread = factoryCreate(\App\Models\Thread::class, ['user_id' => auth()->id()]);

        $this->post($thread->path() . '/lock')->assertStatus(403);

        $this->assertFalse((bool) $thread->fresh()->locked);
    }

    public function test_admin_can_lock_any_thread()
    {
        $this->signInAsAdmin();
        $thread = factoryCreate(\App\Models\Thread::class, ['user_id' => auth()->id()]);

        $this->post($thread->path() . '/lock');

        $this->assertTrue((bool) $thread->fresh()->locked);
    }

    public function test_non_admin_cannot_unlock_thread()
    {
        $this->signInAsAdmin();
        
        $thread = factoryCreate(\App\Models\Thread::class, ['user_id' => auth()->id()]);

        $this->post($thread->path() . '/lock');

        $this->assertTrue((bool) $thread->fresh()->locked);

        $this->signIn();

        $this->post($thread->path() . '/unlock')->assertStatus(403);

        $this->assertTrue((bool) $thread->fresh()->locked);
    }

    public function test_admin_can_unlock_any_thread()
    {
        $this->signInAsAdmin();

        $thread = factoryCreate(\App\Models\Thread::class, ['user_id' => auth()->id()]);

        $this->post($thread->path() . '/unlock');

        $this->assertFalse((bool) $thread->fresh()->locked);
    }
}
