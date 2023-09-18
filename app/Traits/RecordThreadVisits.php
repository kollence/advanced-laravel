<?php

namespace App\Traits;

use Illuminate\Support\Facades\Redis;

trait RecordThreadVisits
{

    protected function visitsCacheKey()
    { 
        return "thread.{$this->id}.visits";
    }

    public function visits()
    {
        return Redis::get($this->visitsCacheKey()) ?? 0;
    }

    public function recordVisit()
    {
        Redis::incr($this->visitsCacheKey());

        return $this;
    }

    public function clearVisits()
    {
        Redis::del($this->visitsCacheKey());

        return $this;
    }

}