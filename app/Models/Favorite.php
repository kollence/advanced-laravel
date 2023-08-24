<?php

namespace App\Models;

use App\Traits\CreateActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory, CreateActivity;

    protected $fillable = ['user_id', 'favorite_id', 'favorite_type'];

    public function favorite()
    {
        return $this->morphTo();
    }
    
}
