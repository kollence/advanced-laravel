<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use HasFactory;

    // protected $guarded = ['id'];
    protected $fillable = ['user_id', 'channel_id', 'title', 'body'];

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
}
