<?php

namespace App\Models;

use App\Models\Scopes\CountScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $fillable = ['user_id', 'channel_id', 'title', 'body'];

    protected static function booted()
    {                               
        // global scope for counting replies that happens before model is booted
                                // using my custom scope class that accepts a relationship to count
        static::addGlobalScope(new CountScope('replies'));
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
}
