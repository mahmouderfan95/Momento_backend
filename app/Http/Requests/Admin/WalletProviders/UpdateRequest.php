<?php

namespace App\Http\Requests\Admin\WalletProviders;

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
        $id = $this->route('wallet_provider');
        return [
            'name' => [
                'sometimes',
                'string',
                Rule::unique('wallet_providers', 'name')
                    ->ignore($id)
            ],
            'status' => 'sometimes|string|in:active,inactive',
        ];
    }
}
