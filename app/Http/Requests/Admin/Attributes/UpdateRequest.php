<?php

namespace App\Http\Requests\Admin\Attributes;

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
            'name' => 'sometimes|array',
            'name.en' => 'sometimes|string',
            'name.ar' => 'sometimes|string',
            'status' => 'sometimes|string|in:active,inactive',
        ];
    }
}
