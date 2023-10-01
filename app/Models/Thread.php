<?php

namespace App\Models;

use App\Events\ThreadHasNewReply;
use App\Redis\CountVisits;
use App\Traits\CreateActivity;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Thread extends Model
{
    use HasFactory, CreateActivity;

    protected $guarded = ['id'];
    protected $fillable = ['user_id', 'channel_id', 'title', 'body', 'slug', 'best_reply_id', 'locked'];
    protected $appends = ['is_subscribed_to'];
    protected $casts = ['locked' => 'boolean'];
                        // 1. here you CAN'T call withoutGlobalScopes() and detached
    protected $with = ['user','channel'];
    protected static function booted()
    {   
        static::created(function ($thread) {
            $thread->update(['slug' => Str::slug($thread->title)]);
        });
        
        // global scope for counting replies that happens before model is booted
                                // using my custom scope class that accepts a relationship to count
        // static::addGlobalScope(new CountScope('replies'));
        // // 2. here you CAN call withoutGlobalScopes() and detached
        // static::addGlobalScope(new UserScope('user'));
        // model events DELETING on delete thread delete his replies
        static::deleting(function ($thread) {
            // delete replies will trigger CreateActivity Trait too
            // so it will delete it activities too
            $thread->replies->each->delete();
        });
    }
    protected function slug(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value) => (static::where('slug', $value)->exists()) ? $value. '-' . $this->id : $value,
        );
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
 
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function channel(){
        return $this->belongsTo(Channel::class);
    }
    // addReply method to be called on thread instance to create reply
    public function addReply(array $reply)
    {   // reply will be created for this relationship
        $reply = $this->replies()->create($reply);  
        event(new ThreadHasNewReply($this, $reply));
        // $this->notifySubscribers($reply);

        return $reply;
    }
    // 
    // public function notifySubscribers($reply)
    // {
    //     $this->subscriptions
    //         ->where('user_id', '!=', $reply->user_id)
    //         ->each->notify($reply);
    // }
    // make url for specific model
    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->slug}";
    }

    public function scopeFilter($query, $filters)
    {
        // call apply method on filters that passes through the query
        return $filters->apply($query);
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);

        return $this;
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()->where('user_id', $userId ?: auth()->id())->delete();
    }

    public function getIsSubscribedToAttribute($userId = null)
    {
        return $this->subscriptions()->where('user_id', $userId ?: auth()->id())->exists();
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function hasUpdatesFor($user)
    {
        $key = $user->visitedThreadCacheKey($this->id);
        return $this->updated_at > cache($key);
    }

    public function visits()
    {
        return new CountVisits($this);
    }

    public function markAsBestReply(Reply $reply)
    {
        $this->update(['best_reply_id' => $reply->id]);
    }

}
