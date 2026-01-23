<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidAuthorName implements Rule
{
    /**
     * Determine if the validation rule passes.
     * Allows only letters (including accents), spaces, dashes, and apostrophes.
     */
    public function passes($attribute, $value): bool
    {
        // Unicode letters, spaces, dashes, apostrophes
        return preg_match("/^[\p{L}\s\-'’]+$/u", $value);
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return 'The :attribute may only contain letters, spaces, dashes, and apostrophes.';
    }
}
