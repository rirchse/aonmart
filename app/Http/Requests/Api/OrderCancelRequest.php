<?php

namespace App\Http\Requests\Api;

use App\Rules\MatchOldPassword;
use Illuminate\Foundation\Http\FormRequest;

class OrderCancelRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'reason' => ['required', 'string', 'max:255']
        ];
    }
}
