<?php

namespace App\Models;

use App\Traits\CreateActivity;
use App\Traits\Favoritable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Reply extends Model
{
    use HasFactory, Favoritable, CreateActivity;

    protected $guarded = ['id'];
    protected $fillable = ['thread_id', 'user_id', 'body'];
    protected $appends = ['favorites_count', 'is_favorited', 'is_best'];

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

    public function mentionedUsersInReply()
    {
        // Regular expression pattern to match "@someName" and second parameter to match only name with out @
        $pattern = '/@([A-Za-z0-9_]+)/';
        // Perform the regular expression match
        preg_match_all($pattern, $this->body, $matches);
        // dd($matches);
        return $matches[1];
    }

    protected function body(): Attribute
    {   // Regular expression pattern to match "@username"
        $pattern = '/@([A-Za-z0-9_]+)/';
        // Replace matches with the desired HTML anchor tag format
        $replacement = '<a href="/profile/$1" class="link">@$1</a>';
        return Attribute::make(
            set: fn (?string $value) => preg_replace($pattern, $replacement, $value),
        );
    }

    protected function isBest(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->isBestReply(),
        );
    }
    
    public function isBestReply(): bool
    {
        return $this->thread->best_reply_id == $this->id; 
    }
}
