<?php

namespace App\Redis;

use Illuminate\Support\Facades\Redis;

class TrendingThreads
{
    const KEY = 'trending_threads';
    const TESTING_KEY = 'testing_trending_threads';

    /**
     * @return mixed
     */
    public function take()
    {
        return array_map('json_decode', Redis::zrevrange($this->cacheKey(), 0, 4));
    }

    /**
     * @return void
     */
    public function put($thread): void
    {
        Redis::zincrby($this->cacheKey(), 1, json_encode([
            'title' => $thread->title,
            'path' => $thread->path(),
        ]));
    }

    public function flush(): void
    {
        Redis::del($this->cacheKey());
    }

    public function cacheKey()
    {
        return (app()->runningUnitTests()) ? self::TESTING_KEY : self::KEY;
    }
}
