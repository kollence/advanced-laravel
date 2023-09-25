<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MarkBestReplyTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_thread_creator_can_mark_any_reply_as_the_best_one()
    {
        $this->signIn();

        $thread = factoryCreate(\App\Models\Thread::class, ['user_id' => auth()->id()]);

        $replies = factoryCreate(\App\Models\Reply::class, ['thread_id' => $thread->id], 2);

        $this->assertFalse($replies[1]->fresh()->isBestReply());

        $this->post(route('mark-best-reply.store', $replies[1]->id));

        $this->assertTrue($replies[1]->fresh()->isBestReply());

        $this->assertFalse($replies[0]->isBestReply());
    }

    public function test_only_the_thread_creator_can_mark_any_reply_as_the_best_one()
    {
        $this->signIn();

        $thread = factoryCreate(\App\Models\Thread::class, ['user_id' => auth()->id()]);

        $replies = factoryCreate(\App\Models\Reply::class, ['thread_id' => $thread->id], 2);

        $this->post(route('mark-best-reply.store', $replies[1]->id));

        $this->assertTrue($replies[1]->fresh()->isBestReply());

        $this->signIn(factoryCreate(\App\Models\User::class));

        $this->post(route('mark-best-reply.store', $replies[1]->fresh()->id))->assertStatus(403);
    }
}
