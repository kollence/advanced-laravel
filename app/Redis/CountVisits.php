<?php

namespace App\Redis;

use Illuminate\Support\Facades\Redis;

class CountVisits
{
    public function __construct(
        protected $thread,
    )
    { /**__construct */ }

    public function flush()
    {
        Redis::del($this->cacheKey());

        return $this;
    }

    public function count()
    {
        return Redis::get($this->cacheKey()) ?? 0;
    }

    protected function cacheKey()
    { 
        return "thread.{$this->thread->id}.visits";
    }

    public function record()
    {
        Redis::incr($this->cacheKey());

        return $this;
    }
}