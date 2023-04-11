<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'barcode'              => ['nullable', 'string', 'max:20', Rule::unique('products')->ignore($this->product)],
      'name'                 => ['required', 'string', 'max:255', Rule::unique('products')->ignore($this->product)],
      'short_description'    => 'nullable|string',
      'full_description'     => 'nullable|string',
      'stock'                => 'nullable|integer',
      'quantity'             => 'required|integer',
      'unit_id'              => 'required|string',
      'category_id'          => 'required|array',
      'category_id.*'        => 'required|string',
      'subcategory_id'       => 'nullable|array',
      'subcategory_id.*'     => 'nullable|string',
      'sub_subcategory_id'   => 'nullable|array',
      'sub_subcategory_id.*' => 'nullable|string',
      'tag_id'               => 'nullable|array',
      'tag_id.*'             => 'nullable|string|distinct',
      'features'             => 'nullable|array',
      'features.*'           => 'nullable|integer|distinct|exists:features,id',
      'regular_price'        => 'required|integer',
      'sell_price'           => 'nullable|integer',
      'discount'             => 'nullable|integer',
      'image'                => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:1024',
      'gallery'              => 'nullable|array',
      'gallery.*'            => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:1024',
    ];
  }
}
