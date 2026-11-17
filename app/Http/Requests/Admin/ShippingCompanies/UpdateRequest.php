<?php

namespace App\Http\Requests\Admin\ShippingCompanies;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

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
            'name' => 'sometimes|string|max:255',
//            'email' => 'required|string|email|unique:shipping_companies,email',
            'phone' => 'sometimes|string|unique:shipping_companies,phone',
            'address' => 'nullable|string|max:255',
            'is_default' => 'nullable|boolean',
        ];
    }
}
