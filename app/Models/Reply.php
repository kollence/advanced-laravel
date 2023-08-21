<?php

namespace App\Models;

use App\Traits\Favoritable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory, Favoritable;

    protected $guarded = ['id'];
    protected $fillable = ['thread_id', 'user_id', 'body'];

    // always load user with model results
    protected $with = ['user', 'favorites'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
