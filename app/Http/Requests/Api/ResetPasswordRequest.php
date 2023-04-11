<?php

namespace App\Http\Requests\Api;

use App\Library\Utilities;
use App\Rules\EmailOrPhoneValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email_or_phone' => ['required', new EmailOrPhoneValidationRule()],
            'otp' => ['required', 'digits:6', 'numeric'],
            'new_password' => Utilities::getValidationRule(Utilities::VALIDATION_RULE_PASSWORD)
        ];
    }
}
