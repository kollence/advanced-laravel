<?php

namespace App\Utilities;

class Spam
{

    public function detect($text)
    {
        $this->detectInvalidKeywords($text);
        return false;

    }

    protected function detectInvalidKeywords($text)
    {
        $spam_words = [
            // often spam words
            'porn',
            'sex',
            'xxx',
            'fuck',
            'pussy',
            'spam text'
        ];

        $spam_words = array_map('strtolower', $spam_words);

        // get the text
        $text = strtolower($text);

        foreach ($spam_words as $word) {
            if (strpos($text, $word) !== false) {
                throw new \Exception("Spam detected!");
            }
        }
    }
}