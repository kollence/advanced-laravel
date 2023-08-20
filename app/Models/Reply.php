<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $fillable = ['thread_id', 'user_id', 'body'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorite');
    }

    public function addFavorite()
    {
        $this->favorites()->create(['user_id' => auth()->id()]);
    }
    public function removeFavorite()
    {
        $this->favorites()->where('user_id', auth()->id())->delete();
    }
    public function isFavorited()
    {
        return (bool) $this->favorites()->where('user_id', auth()->id())->count();
    }

}
