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

    // //PROBLEM: THIS TEST CAN NOT BE USED IN TESTING BECAUSE OF PROBLEM WITH DELETING ON TABLE MIGRATION LVL WITH SQLITE FOREIGN KEY
    //      // WHEN REPLY IS DELETED THEN THE BEST_REPLY_ID IS NOT SET ON NULL FROM THE THREADS TABLE
    //      // BUT IT WORKS IN DEVELOPMENT ENVIRONMENT.
    // SOLUTION: MOVE `REPLY` TABLE MIGRATION UP SO IT IS CREATED BEFORE THE `THREADS` TABLE
    //      AND IN `THREAD` TABLE ADDED FOREIGN ID FOR `BEST_REPLY_ID` THAT REFERENCE TO TABLE `REPLY` WILL WORK
    //      (that is only way. add_foreign_key_migration will not help) BAD FOR PRODUCTION
    public function test_when_best_reply_is_deleted_then_delete_threads_best_reply_id()
    {
        $this->signIn();

        $thread = factoryCreate(\App\Models\Thread::class, ['user_id' => auth()->id()]);

        $replies = factoryCreate(\App\Models\Reply::class, ['thread_id' =>$thread->id], 2);

        $this->post(route('mark-best-reply.store', $replies[1]->id));

        $this->assertTrue($replies[1]->fresh()->isBestReply());

        $replies[1]->delete();
        
        $this->assertNull($thread->fresh()->best_reply_id);
    }

    public function test_un_mark_best_reply()
    {
        $this->signIn();

        $thread = factoryCreate(\App\Models\Thread::class, ['user_id' => auth()->id()]);
        $replies = factoryCreate(\App\Models\Reply::class, ['thread_id' =>$thread->id], 2);

        $this->post(route('mark-best-reply.store', $replies[1]->id));

        $this->assertTrue($replies[1]->fresh()->isBestReply());

        $this->post(route('un-mark-best-reply.destroy', $replies[1]->id));

        $this->assertFalse($replies[1]->fresh()->isBestReply());
    }

    public function test_only_the_thread_creator_can_un_mark_any_best_reply()
    {
        $this->signIn();

        $thread = factoryCreate(\App\Models\Thread::class, ['user_id' => auth()->id()]);
        $reply1 = factoryCreate(\App\Models\Reply::class, ['thread_id' =>$thread->id]);

        $this->post(route('mark-best-reply.store', $reply1->id));
        $this->assertTrue($reply1->fresh()->isBestReply());

        $this->signIn();


        $this->post(route('un-mark-best-reply.destroy', $reply1->id))->assertStatus(403);

        $this->assertTrue($reply1->fresh()->isBestReply());
    }
}
