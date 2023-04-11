<?php

namespace App\Http\Requests;

use App\Models\Category;
use App\Models\District;
use App\Models\Division;
use App\Models\Store;
use App\Models\Upazila;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return TRUE;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', Rule::unique(Store::class, 'name')->ignore($this->store), 'max:255'],
            'categories' => ['required', 'array'],
            'categories.*' => [Rule::exists(Category::class, 'id')],
            'address' => ['required', 'string'],
            'shipping_fee' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:512'],
            'status' => ['required', 'boolean'],
            'capital' => ['nullable', 'numeric'],
            'capital_note' => ['nullable', 'string']
        ];
    }
}
