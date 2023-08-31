<?php

namespace App\Models;

use App\Notifications\ThreadWasReplied;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThreadSubscription extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notify($reply)
    {
        $this->user->notify(new ThreadWasReplied($this, $reply));
    }
}
