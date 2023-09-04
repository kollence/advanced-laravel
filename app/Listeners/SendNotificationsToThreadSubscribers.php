<?php

namespace App\Listeners;

use App\Events\ThreadHasNewReply;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNotificationsToThreadSubscribers
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ThreadHasNewReply  $event
     * @return void
     */
    public function handle(ThreadHasNewReply $event)
    {
        $event->thread->subscriptions
            ->where('user_id', '!=', $event->reply->user_id)
            ->each->notify($event->reply);
    }
}
