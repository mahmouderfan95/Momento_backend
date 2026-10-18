<?php

namespace App\Http\Requests\Admin\Products;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'array'],
            'name.en' => ['required', 'string', 'max:255',Rule::unique('products', 'name->en')],
            'name.ar' => ['nullable', 'string', 'max:255'],

            'description' => ['required', 'array'],
            'description.en' => ['required', 'string'],
            'description.ar' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'price_after_discount' => ['nullable', 'numeric', 'lte:price'],
            'available_quantity' => ['required', 'integer', 'min:0'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'category_id' => ['required', 'exists:categories,id'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            'attribute_option_ids' => ['nullable', 'array'],
            'attribute_option_ids.*' => ['exists:attribute_options,id'],
        ];
    }
}
