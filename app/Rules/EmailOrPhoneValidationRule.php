<?php

namespace App\Rules;

use App\Library\Utilities;
use Illuminate\Contracts\Validation\Rule;

class EmailOrPhoneValidationRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        return Utilities::isValidPhoneNumber($value)
            or Utilities::isValidEmail($value);
    }

    public function message(): string
    {
        return 'The :attribute is invalid.';
    }
}
