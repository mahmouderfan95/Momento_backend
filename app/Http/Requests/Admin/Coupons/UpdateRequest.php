<?php

namespace App\Http\Requests\Admin\Coupons;

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
        $id = $this->route('coupon');
        return [
            'code' => ['sometimes', 'string', 'max:64', Rule::unique('coupons', 'code')->ignore($id)],
            'value' => ['sometimes', 'numeric', 'min:0.01'],
            'min_order_total' => ['sometimes', 'numeric', 'min:0'],
            'usage_limit' => ['sometimes', 'integer', 'min:1'],
            'status' => ['sometimes','in:active,inactive','string'],
            'start_date' => ['sometimes', 'date'],
            'end_date' => ['sometimes', 'date', 'after_or_equal:start_date'],
        ];
    }
}
