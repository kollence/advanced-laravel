<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_img',
        'confirmed_email',
        'confirmation_token',
    ];
    
    // set name as route model binding
    public function getRouteKeyName()
    {
        return 'name';
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'confirmed_email' => 'boolean',
    ];

    public function threads(){
        return $this->hasMany(Thread::class);
    }

    public function isAdmin()
    {
        return $this->name === 'Admin';
    }

    public function activity()
    {
        return $this->hasMany(Activity::class);
    }

    public function visitedThreadCacheKey($threadId)
    {
        return sprintf('user.%s.visits.%s', $this->id, $threadId);
    }

    public function read($thread)
    {
        $key = $this->visitedThreadCacheKey($thread->id);
        cache()->forever($key, Carbon::now());
    }

    public function lastReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }

    public function avatar()
    {
        return $this->avatar_img ? 'storage/'.$this->avatar_img : 'storage/avatars/default.png';
    }
}
