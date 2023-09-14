<?php

namespace Tests\Feature;

use Database\Factories\DatabaseNotificationFactory;
use Tests\TestCase;

class NotificationTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();

        $this->signIn();
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_auth_can_visit_notification_page()
    {
        $this->get(route('profile.notifications.index', auth()->user()->name))->assertStatus(200);
    }

    public function test_notification_is_prepared_when_subscribed_thread_got_a_new_reply_that_is_not_created_by_current_subscribed_user()
    {
        //create thread
        $thread = factoryCreate(\App\Models\Thread::class)->subscribe();
        // check that subscribed thread doesn't have a notification
        $this->assertCount(0, auth()->user()->notifications);
        // reply created by me
        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'My reply will not notify me'
        ]);
        // make sure that notification is not created for current subscribed user
        $this->assertCount(0, auth()->user()->fresh()->notifications);
        // reply created from other user
        $thread->addReply([
            'user_id' => factoryCreate(\App\Models\User::class)->id,
            'body' => 'Others users reply will notify me'
        ]);
        // reply from others will notify me
        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }

    public function test_auth_can_fetch_their_unread_notifications()
    {
        // FACTORY call for generated Laravels Model Illuminate\Notifications\DatabaseNotification
        // create a notification
        DatabaseNotificationFactory::new()->create();

        $response = $this->getJson(route('profile.notifications.index', auth()->user()))
                    ->assertOk();
        
        $this->assertCount(1, $response->json()['data']);
    }

    public function test_an_auth_can_mark_a_notification_as_read()
    {
        $user = auth()->user();
        // FACTORY call for generated Laravels Model Illuminate\Notifications\DatabaseNotification
        // create a notification
        DatabaseNotificationFactory::new()->create();

        $this->assertCount(1, $user->unreadNotifications);
        
        $this->delete(route('profile.notifications.delete', [$user->name, $user->unreadNotifications->first()->id]));

        $this->assertCount(0, $user->fresh()->unreadNotifications);
    }
}
