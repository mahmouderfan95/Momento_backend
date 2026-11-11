<?php

namespace App\Http\Requests\Admin\WalletNumbers;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

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
            'number' => ['required', 'string', 'regex:/^01[0-9]{9}$/'],
            'status' => 'required|string|in:active,inactive',
            'wallet_provider_id' => 'required|exists:wallet_providers,id',
        ];
    }
}
