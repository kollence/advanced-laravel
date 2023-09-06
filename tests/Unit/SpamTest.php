<?php

namespace Tests\Unit;

use App\Utilities\Inspections\Spam;
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
        //Exception thrown from Spam::class
        $this->expectException('\Exception');

        $spam->detect('spam text');
    }

    public function test_detect_if_any_key_being_held_down()
    {   
        $spam = new Spam();
        //Exception thrown from Spam::class
        $this->expectException('\Exception');
                                        //more then 4 time should throw an Exception
        $spam->detect('key being held downnnnn');
    }
}
