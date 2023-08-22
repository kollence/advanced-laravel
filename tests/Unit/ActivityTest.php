<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;

use App\Models\Activity;
use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_create_activity_when_thread_is_created()
    {
        $this->signIn();

        $thread = factoryCreate(Thread::class);

        $this->assertDatabaseHas('activities', [
            'user_id' => auth()->id(),
            'subject_id' => $thread->id,
            'type' => 'created.thread',
            'subject_type' => Thread::class,
        ]);

        $activity = Activity::first(); 
        $this->assertEquals($activity->subject->id, $thread->id);
    }

    public function test_create_activity_when_reply_is_created()
    {
        $this->signIn();

        $reply = factoryCreate(Reply::class);


        $activity = Activity::count(); 
        $this->assertEquals(2, $activity);
    }
}
