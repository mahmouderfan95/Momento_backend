<?php

namespace App\Http\Requests\Admin\Settings;

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
            'site_name'           => ['required', 'array'],
            'site_name.ar'        => ['required', 'string', 'max:255'],
            'site_name.en'        => ['required', 'string', 'max:255'],
            'site_description'    => ['nullable', 'array'],
            'site_description.ar' => ['nullable', 'string', 'max:2000'],
            'site_description.en' => ['nullable', 'string', 'max:2000'],
            'site_address'        => ['nullable', 'string'],
            'site_logo'           => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'site_phone'          => ['nullable', 'string', 'max:20'],
            'whatsapp'            => ['nullable', 'string', 'max:20'],
            'facebook'            => ['nullable', 'url:https', 'max:255'],
            'tiktok'              => ['nullable', 'url:https', 'max:255'],
            'instagram'           => ['nullable', 'url:https', 'max:255'],
        ];
    }
}
