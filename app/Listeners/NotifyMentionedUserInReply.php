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
        // Regular expression pattern to match "@someName" and second parameter to match only name with out @
        $pattern = '/@([A-Za-z0-9_]+)/';
        // Perform the regular expression match
        preg_match_all($pattern, $event->reply->body, $matches);
        $names = $matches[1];// dd($matches);
        foreach($names as $name){
            $user = User::where('name', $name)->first();
            if($user){
                $user->notify(new YouAreMentionedInRepy($event->reply));
            }
            
        }
    }
}
