<?php

namespace App\Http\Requests\Api;

use App\Rules\EmailOrPhoneRule;
use App\Rules\EmailOrPhoneValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class VerifyForgetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email_or_phone' => ['required', new EmailOrPhoneValidationRule()]
        ];
    }
}
