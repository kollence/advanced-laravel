<?php

namespace App\Utilities\Inspections;

use Exception;

class InvalidKeyWords
{
    /**
     * @var array
     */
    protected $spam_words = [
        // often spam words
        'porn',
        'sex',
        'xxx',
        'fuck',
        'pussy',
        'spam text'
    ];


    public function detect($text)
    {
        $spam_words = array_map('strtolower', $this->spam_words);
        // get the text
        $text = strtolower($text);

        foreach ($spam_words as $word) {
            if (strpos($text, $word) !== false) {
                throw new Exception("Spam detected!");
            }
        }
    }
}