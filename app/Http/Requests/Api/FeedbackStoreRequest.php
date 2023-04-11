<?php

namespace App\Http\Requests\Api;

use App\Models\Feedback;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FeedbackStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reference' => ['required', Rule::in(array_keys(Feedback::REFERENCES))],
            'reference_id' => ['required', 'numeric', Rule::exists(Feedback::REFERENCES[$this->request->get('reference')], 'id')],
            'content' => ['required', 'string']
        ];
    }
}
