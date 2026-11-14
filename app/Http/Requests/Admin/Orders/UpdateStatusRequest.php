<?php

namespace App\Http\Requests\Admin\Orders;

use App\Enums\OrderStatusEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStatusRequest extends FormRequest
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
            'status'        => ['required', Rule::enum(OrderStatusEnum::class)],
            'cancel_reason' => ['nullable', 'string', 'max:500',
                Rule::requiredIf(fn () => $this->status === OrderStatusEnum::Cancelled->value)],
        ];
    }
}
