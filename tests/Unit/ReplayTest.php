<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
// use PHPUnit\Framework\TestCase;
use Tests\TestCase;


class ReplyTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_reply_has_user()
    {
        $reply = \App\Models\Reply::factory()->create();
        // reply have instance of user (user created reply)
        $this->assertInstanceOf('App\Models\User', $reply->user);
    }
}
