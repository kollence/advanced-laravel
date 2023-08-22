<?php

namespace App\Traits;

use App\Models\Activity;

trait CreateActivity 
{
    protected function createActivityWhenThreadIsCreated($event)
    {
        Activity::create([
            'user_id' => auth()->id(),
            'type' => $event . '.' . $this->getClassNameToLowercase(),
            'subject_id' => $this->id,
            'subject_type' => get_class($this)
        ]);
    }

    protected function getClassNameToLowercase()
    {
        return strtolower(class_basename($this));
    }
}