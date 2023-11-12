<?php

namespace Tests\Feature;

use App\Events\NewContestEmailReceivedEvent;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ContestRegistrationTest extends TestCase
{
    // public function setUp() : void
    // {
    //     //initialize one $thread as ready & created with factory
    //     parent::setUp();   
     
            // IF ITS IS HERE GLOBAL EVENTS WILL NOT WORK FOR TRIGGERING LISTENERS
    //     Event::fake([
    //         NewContestEmailReceivedEvent::class
    //     ]);
    // }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_storing_contest_email_can_be_entered_into_the_contest_email_table()
    {
        $this->post('/contest', [
            'email' => 'test@test.com'
        ]);

        $this->assertDatabaseCount('contest_emails', 1);
    }

    public function test_storing_contest_email_request_must_be_valid()
    {
        //can't be empty
        $this->post('/contest', [
            'email' => ''
        ]);
        
        $this->assertDatabaseCount('contest_emails', 0);
        //needs to be valid email format
        $this->post('/contest', [
            'email' => 'test_test_com'
        ]);

        $this->assertDatabaseCount('contest_emails', 0);
        //regular is passing and storing
        $this->post('/contest', [
            'email' => 'test@test.com'
        ]);

        $this->assertDatabaseCount('contest_emails', 1);
    }

    public function test_storing_contest_email_must_trigger_event()
    {
        Event::fake([
            NewContestEmailReceivedEvent::class
        ]);
        
        $this->post('/contest', [
            'email' => 'test@test.com'
        ]);

        Event::assertDispatched(NewContestEmailReceivedEvent::class);
    }

    public function test_storing_contest_email_must_trigger_event_that_send_email_notification()
    {
        // RUN JUST THIS ONE SO YOU CAN CHECK TRIGGERING OF LISTENER::class
        $this->post('/contest', [
            'email' => 'test@test.com'
        ]);
    }
}
