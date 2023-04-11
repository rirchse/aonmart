<?php

namespace App\Rules;

use Auth;
use Hash;
use Illuminate\Contracts\Validation\Rule;

class MatchOldPassword implements Rule
{
    public function passes($attribute, $value): bool
    {
        return Hash::check($value, Auth::user()->password);
    }

    public function message(): string
    {
        return 'The :attribute didn\'t matched.';
    }
}
