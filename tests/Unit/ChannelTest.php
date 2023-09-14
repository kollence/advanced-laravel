<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;

use App\Models\Channel;
use App\Models\Thread;
use Tests\TestCase;

class ChannelTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_a_channel_consist_of_threads()
    {
        $channel = factoryCreate(Channel::class);
        $thread = factoryCreate(Thread::class, ['channel_id' => $channel->id]);

        $this->assertTrue($channel->threads->contains($thread));
    }
}
