<?php

namespace App\Traits;

use App\Models\Activity;

trait CreateActivity 
{

    // trait listen when modal is created and it will generate a new activity
    protected static function bootCreateActivity()
    {
        if(auth()->check()){
            static::created(function ($thread) {
                $thread->createActivityWhenThreadIsCreated('created');
            });
        }
    }

    protected function createActivityWhenThreadIsCreated($event)
    {
        // Activity::create([
        //     'user_id' => auth()->id(),
        //     'type' => $this->getActivityType($event),
        //     'subject_id' => $this->id,
        //     'subject_type' => get_class($this)
        // ]);
        $this->activity()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event),
        ]);
    }

    protected function getActivityType($event)
    {   $type = strtolower(class_basename($this));
        return "{$event}.{$type}";
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject');
    }
}