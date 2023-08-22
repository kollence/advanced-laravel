<?php

namespace App\Traits;

use App\Models\Favorite;

trait Favoritable
{
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
        return (bool) $this->favorites->where('user_id', auth()->id())->count();
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }
}