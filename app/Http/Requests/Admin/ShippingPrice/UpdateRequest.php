<?php

namespace App\Http\Requests\Admin\ShippingPrice;

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
//            'shipping_company_id' => 'required|exists:shipping_companies,id|unique:shipping_prices,shipping_company_id',
//            'city_id' => 'required|exists:cities,id|unique:shipping_prices,city_id',
//            'region_id' => 'required|exists:regions,id',
            'price' => 'sometimes|numeric',
        ];
    }
}
