<?php

namespace App\Models;

use App\Events\NewContestEmailReceivedEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContestEmails extends Model
{
    use HasFactory;

    protected $guarded = [];


    protected static function booted()
    {
        static::created(function ($contestEmail) {
            // NewContestEmailReceivedEvent::dispatch(); or you can use ::dispatch() on NewContestEmailReceivedEvent as new syntax
            event(NewContestEmailReceivedEvent::class);
        });
    }
}
