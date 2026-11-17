<?php

namespace App\Http\Requests\Admin\Sliders;

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
            'title' => 'sometimes|array',
            'title.en' => 'sometimes|string',
            'title.ar' => 'sometimes|string',
            'description' => 'nullable|array',
            'description.en' => 'nullable|string',
            'description.ar' => 'nullable|string',
            'image' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'sometimes|string|in:active,inactive',
        ];
    }
}
