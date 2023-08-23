<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'type' ,
        'user_id' ,
        'subject_id' ,
        'subject_type' ,
        'created_at' ,
    ];

    public function subject()
    {
        return $this->morphTo();
    }

    public static function feed($user, $amount = 20)
    {
        // return $user->activity()
        return static::where('user_id', $user->id)
            ->latest()
            ->with('subject')
            ->take($amount)
            ->get()
            ->groupBy(fn ($activity) => $activity->created_at->format('Y-m-d'));
    }
}
