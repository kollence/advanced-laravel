<?php

namespace App\Models;

use App\Models\Scopes\CountScope;
use App\Models\Scopes\UserScope;
use App\Traits\CreateActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use HasFactory, CreateActivity;

    protected $guarded = ['id'];
    protected $fillable = ['user_id', 'channel_id', 'title', 'body'];
    protected $appends = ['is_subscribed_to'];
                        // 1. here you CAN'T call withoutGlobalScopes() and detached
    protected $with = ['user','channel'];
    protected static function booted()
    {   // global scope for counting replies that happens before model is booted
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
    // // cistom getter to return count of replies
    // public function getRepliesCountAttribute()
    // {
    //     return $this->replies()->count();
    // }
 
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
        $this->replies()->create($reply);
    }
    // make url for specific model
    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
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
}
