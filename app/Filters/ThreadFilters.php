<?php

namespace App\Filters;

use App\Models\User;

class ThreadFilters extends Filters
{
    protected $filters = ['by', 'popular', 'unanswered'];
    /**
     * Filter query by a given usernam
     * 
     * @param  string $username
     * @return mixed
     */
    public function by($username)
    {
        $user = User::where('name', $username)->firstOrFail();
        return $this->builder->where('user_id', $user->id);
    }

    public function popular()
    {
        $this->builder->getQuery()->orders = [];
        return $this->builder->orderBy('replies_count', 'desc');
    }

    public function unanswered()
    {
        $this->builder->getQuery()->orders = [];
        return $this->builder->whereDoesntHave('replies')->get();
    }
}