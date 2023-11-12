<?php

namespace App\Providers;

use App\Events\NewContestEmailReceivedEvent;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\ThreadHasNewReply;
use App\Listeners\AssignedToContestNotification;
use App\Listeners\NotifyMentionedUserInReply;
use App\Listeners\SendEmailConfirmation;
use App\Listeners\SendNotificationsToThreadSubscribers;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            SendEmailConfirmation::class
        ],
        ThreadHasNewReply::class => [
            SendNotificationsToThreadSubscribers::class,
            NotifyMentionedUserInReply::class
        ],
        NewContestEmailReceivedEvent::class => [
            AssignedToContestNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
