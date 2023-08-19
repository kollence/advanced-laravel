<?php

function factoryCreate($class, $attributes = [], $quantity = null)
{    
    if($quantity){
        return $class::factory()->count($quantity)->create($attributes);
    }
    return $class::factory()->create($attributes);
}

function factoryMake($class, $attributes = [], $quantity = null)
{
    if($quantity){
        return $class::factory()->count($quantity)->make($attributes);
    }
    return $class::factory()->make($attributes);
}