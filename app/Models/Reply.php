<?php

namespace App\Models;

use App\Traits\CreateActivity;
use App\Traits\Favoritable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory, Favoritable, CreateActivity;

    protected $guarded = ['id'];
    protected $fillable = ['thread_id', 'user_id', 'body'];
    protected $appends = ['favorites_count', 'is_favorited'];

    // always load user with model results
    protected $with = ['user', 'favorites'];
    
    protected static function booted()
    {
        static::created(function ($reply) {
            $reply->thread->increment('replies_count');
        });

        static::deleted(function ($reply) {
            $reply->thread->decrement('replies_count');
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }
    
    public function path()
    {
        return $this->thread->path() . '#reply-' .$this->id; 
    }

    public function favorite()
    {
        return $this->hasOne(Favorite::class);
    }

    public function ifJustPublishedReply()
    {
        //Sub one minute to the instance (using date interval).
        return $this->created_at->gt(Carbon::now()->subMinute());
    }
}
