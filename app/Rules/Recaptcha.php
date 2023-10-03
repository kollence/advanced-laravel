<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;

class Recaptcha implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
            $google_response = 'https://www.google.com/recaptcha/api/siteverify';
            $secret = config('services.recaptcha.secret');
            $response = Http::asForm()->post($google_response, [
                'secret' => $secret,
                'response' => $value,
                'remoteip' => request()->ip()
            ]);
            $response = json_decode($response->body());
            
            return $response->success;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The reCAPTCHA verification has failed. Please try again.';
    }
}
