<?php

namespace App\Traits;

use App\Models\Activity;

trait CreateActivity 
{

    // trait listen when modal is created and it will generate a new activity
    protected static function bootCreateActivity()
    {       // if not auth user return (TESTING WILL WORK FOR GUEST USERS)
            if(!auth()->check()) return;

            foreach(static::getAvailableEvents() as $event) {
                static::created(function ($model) use($event) {
                    $model->createActivityWhenThreadIsCreated($event);
                });
            }

            static::deleting( function ($model) {
                 $model->activity()->delete(); 
            });
    }

    protected static function getAvailableEvents()
    {
        return ['created'];
    }


    protected function createActivityWhenThreadIsCreated($event)
    {
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