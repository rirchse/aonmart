<?php

namespace App\Http\Requests;

use App\Models\Slider;
use App\Rules\ImageValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SliderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return TRUE;
    }

    public function rules(): array
    {
        return [
            'image' => [Rule::requiredIf(!$this->slider?->image), new ImageValidationRule()],
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'button_text' => ['nullable', 'string', 'max:255'],
            'button_link' => ['nullable', 'url', 'max:255'],
            'type' => ['required', Rule::in(array_keys(Slider::TYPES))],
            'status' => ['required', 'boolean']
        ];
    }
}
