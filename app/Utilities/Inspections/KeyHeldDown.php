<?php

namespace App\Utilities\Inspections;

use Exception;

class KeyHeldDown
{
    public function detect($text)
    {
        // Define a regular expression pattern to match the same letter appearing more than 4 times in a row
        $pattern = '/(.)\1{4,}/';
        if (preg_match($pattern, $text)) {
            throw new Exception("Spam detected!");
        }
    }
}