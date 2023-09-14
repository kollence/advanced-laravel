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
        $reply = factoryCreate(\App\Models\Reply::class);
        // reply have instance of user (user created reply)
        $this->assertInstanceOf('App\Models\User', $reply->user);
    }

    public function test_reply_was_just_published()
    {
        $reply = factoryCreate(\App\Models\Reply::class);
        // reply was just published
        $this->assertTrue($reply->ifJustPublishedReply());
        // reply was not just published
        $reply->created_at = $reply->created_at->subHour();
        $this->assertFalse($reply->ifJustPublishedReply());
    }

    public function test_it_can_detect_all_mentioned_users_in_the_reply()
    {
        $reply = factoryCreate(\App\Models\Reply::class, ['body' => '@JaneDoe and @JohnDoe']);
        // reply has mentioned users
        $this->assertEquals(['JaneDoe', 'JohnDoe'], $reply->mentionedUsersInReply());
    }

    public function test_it_wraps_mentioned_users_in_the_reply_with_anchor_tags()
    {
        $reply = factoryCreate(\App\Models\Reply::class, ['body' => 'Hello @JaneDoe']);
        // reply has mentioned users
        $this->assertEquals('Hello <a href="/profile/JaneDoe" class="link">@JaneDoe</a>', $reply->body);
    }
}
