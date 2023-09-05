<?php

namespace Tests\Unit;

use App\Utilities\Spam;
use PHPUnit\Framework\TestCase;

class SpamTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_detect_spam_text()
    {   
        $spam = new Spam();

        $this->assertFalse($spam->detect('innocent text'));

        $this->expectException('\Exception');
        $spam->detect('spam text');
    }
}
