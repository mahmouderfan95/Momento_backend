<?php

namespace App\Http\Requests\Front\Carts;

use App\Support\FavoriteOwner;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCartItemRequest extends FormRequest
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
        return ['quantity' => ['required', 'integer', 'min:1', 'max:20']];
    }
    public function owner(): FavoriteOwner
    {
        return FavoriteOwner::fromRequest($this);
    }
}
