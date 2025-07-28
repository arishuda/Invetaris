<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Mews\Captcha\Facades\Captcha;

class CustomCaptcha implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return Captcha::check($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The CAPTCHA is incorrect.';
    }
}
