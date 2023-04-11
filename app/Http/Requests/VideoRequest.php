<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Rules\ImageValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class VideoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'thumbnail' => [Rule::requiredIf($this->video == null), new ImageValidationRule()],
            'title' => ['required', 'string' , 'max:255'],
            'link' => ['required', 'active_url', Rule::unique('videos')->ignore($this->video)],
            'status' => ['required', 'boolean']
        ];
    }
}
