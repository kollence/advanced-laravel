<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserNotificationsController extends Controller
{

    public function index(User $user)
    {
        $notifications = auth()->user()->unreadNotifications()->paginate(10);
        return $notifications;
        // return view('user.notifications.index', compact('notifications'));
    }


    public function destroy(User $user, $notificationId)
    {                                                // mark as read update column read_at in notification table
        auth()->user()->notifications()->findOrFail($notificationId)->markAsRead();
        return back();
    }
}
