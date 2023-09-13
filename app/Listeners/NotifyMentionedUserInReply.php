<?php

namespace App\Listeners;

use App\Events\ThreadHasNewReply;
use App\Models\User;
use App\Notifications\YouAreMentionedInRepy;

class NotifyMentionedUserInReply
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
        $names = $event->reply->mentionedUsersInReply();
        User::whereIn('name', $names)
        ->get()
        ->each(function ($user) use ($event) {
            $user->notify(new YouAreMentionedInRepy($event->reply));
        });
    }
}
