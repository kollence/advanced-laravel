<?php

namespace Tests\Feature;

use App\Models\Thread;
use App\Redis\TrendingThreads;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class TrendingThreadsTest extends TestCase
{
    protected $trendingThreads;

    protected function setUp() : void
    {
        parent::setUp();
        $this->trendingThreads = new TrendingThreads();
        //delete if its exist
        $this->trendingThreads->flush();
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_increment_a_threads_score_each_time_it_is_read()
    {   
        //no one red the thread and count its 0
        $this->assertEmpty($this->trendingThreads->take());

        $thread = factoryCreate(Thread::class);
        //go to thread and show it to me so i can read
        $this->get($thread->path())->assertSee($thread->title);
        //thread is read and count is 1
        $this->assertCount(1, $this->trendingThreads->take());
        
        $redisTrendingThread = $this->trendingThreads->take();
        // compare returned result from redis with the thread title
        $this->assertEquals($thread->title, $redisTrendingThread[0]->title);

    }

    public function test_it_can_cleanup_redis_after_testing()
    {
        // Delete the test key from Redis
        $this->trendingThreads->flush();

        // Assert that the key no longer exists in Redis
        $this->assertNull(Redis::get('testing_trending_treads'));
    }
}
