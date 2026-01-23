<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidTitleName implements Rule
{
    /**
     * Determine if the validation rule passes.
     * Allows letters (including accents), numbers, spaces, dashes, and periods.
     */
    public function passes($attribute, $value): bool
    {
        // Unicode letters, numbers, spaces, dashes, periods only
        return preg_match('/^[\p{L}\p{N}\s\-.]+$/u', $value);
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return 'The :attribute may only contain letters, numbers, spaces, dashes, and periods.';
    }
}
