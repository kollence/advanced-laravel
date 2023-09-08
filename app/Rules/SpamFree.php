<?php

namespace App\Rules;

use App\Utilities\Inspections\Spam;
use Illuminate\Contracts\Validation\InvokableRule;

class SpamFree implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        try {

            return !resolve(Spam::class)->detect($value);

        } catch (\Exception $th) {
            
            $fail('Forbidden text: '. $th->getMessage());
        }
    }
}
