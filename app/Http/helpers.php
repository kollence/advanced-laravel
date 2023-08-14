<?php

function factoryCreate($class, $attributes = [])
{    
    if(!empty($attributes)){
        return $class::factory()->create($attributes);
    }
    return $class::factory()->create();
}
function factoryMake($class, $attributes = [])
{
    if(!empty($attributes)){
        return $class::factory()->make($attributes);
    }
    return $class::factory()->make();
}