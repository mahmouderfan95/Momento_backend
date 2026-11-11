<?php

namespace App\Http\Requests\Admin\WalletNumbers;

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
            'number' => ['sometimes', 'string', 'regex:/^01[0-9]{9}$/'],
            'status' => 'sometimes|string|in:active,inactive',
            'wallet_provider_id' => 'sometimes|exists:wallet_providers,id',
        ];
    }
}
