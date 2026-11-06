<?php

namespace App\Http\Requests\Front\Carts;

use App\Support\FavoriteOwner;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddToCartRequest extends FormRequest
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
            'product_id'            => ['required', 'integer', Rule::exists('products', 'id')->where('status', 'active')],
            'quantity'              => ['required', 'integer', 'min:1', 'max:20'],
            'attribute_option_ids'  => ['sometimes', 'array'],
            'attribute_option_ids.*'=> ['integer', 'distinct'],
        ];
    }
    public function owner(): FavoriteOwner
    {
        return FavoriteOwner::fromRequest($this);
    }
}
