<?php

namespace App\Utilities\Inspections;

class Spam
{

    protected $inspections = [
        InvalidKeyWords::class,
        KeyHeldDown::class,
    ];

    public function detect($text)
    {
        foreach ($this->inspections as $inspection) {
            app($inspection)->detect($text);
        }
        return false;
    }




}