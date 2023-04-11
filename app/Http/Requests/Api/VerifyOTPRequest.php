<?php

namespace App\Http\Requests\Api;

use App\Rules\EmailOrPhoneValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class VerifyOTPRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email_or_phone' => ['required', new EmailOrPhoneValidationRule()],
            'otp' => ['required', 'digits:6', 'numeric']
        ];
    }
}
