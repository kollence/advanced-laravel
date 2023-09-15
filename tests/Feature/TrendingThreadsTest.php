<?php

namespace Tests\Feature;

use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class TrendingThreadsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_increment_a_threads_score_each_time_it_is_read()
    {   
        //delete if its exist
        Redis::del('trending_treads');
        //no one red the thread and count its 0
        $this->assertEmpty(Redis::zrevrange('trending_treads', 0, -1));

        $thread = factoryCreate(Thread::class);
        //go to thread and show it to me so i can read
        $this->get($thread->path())->assertSee($thread->title);
        //thread is read and count is 1
        $this->assertCount(1, Redis::zrevrange('trending_treads', 0, -1));
        
        $redisTrendingThread = Redis::zrevrange('trending_treads', 0, -1);
        // compare returned result from redis with the thread title
        $this->assertEquals($thread->title, json_decode($redisTrendingThread[0])->title);

    }
}
