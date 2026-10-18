<?php

namespace App\Http\Requests\Admin\Products;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
            'name' => ['sometimes', 'array'],
            'name.en' => ['sometimes', 'string', 'max:255'],
            'name.ar' => ['sometimes', 'string', 'max:255'],

            'description' => ['sometimes', 'array'],
            'description.en' => ['sometimes', 'string'],
            'description.ar' => ['sometimes', 'string'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'price_after_discount' => ['nullable', 'numeric', 'lte:price'],
            'available_quantity' => ['sometimes', 'integer', 'min:0'],
            'status' => ['sometimes', Rule::in(['active', 'inactive'])],
            'category_id' => ['sometimes', 'exists:categories,id'],
            'image' => ['sometimes', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            'attribute_option_ids' => ['nullable', 'array'],
            'attribute_option_ids.*' => ['exists:attribute_options,id'],
        ];
    }
}
