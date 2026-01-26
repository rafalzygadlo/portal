<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Profanity implements Rule
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
        $forbiddenWords = ['kurwa', 'cholera', 'chuj', 'pierdol'];
        foreach ($forbiddenWords as $word) {
            if (stripos($value, $word) !== false) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Komentarz zawiera niedozwolone słowa.';
    }
}
