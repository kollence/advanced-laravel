<?php

namespace Tests\Unit;

use App\Models\Reply;
use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_user_can_fetch_last_reply()
    {
        $user = factoryCreate(User::class);  

        $reply = factoryCreate(Reply::class, ['user_id' => $user->id]);

        $this->assertEquals($reply->id, $user->lastReply->id);

    }
}
