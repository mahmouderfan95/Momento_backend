<?php

namespace App\Http\Requests\Front\Favorites;

use App\Support\FavoriteOwner;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
class FavoriteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return FavoriteOwner::fromRequest($this)->isIdentified();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
    protected function failedAuthorization(): void
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'يجب إرسال توكن الدخول أو X-Guest-Token',
        ], 401));
    }

    public function owner(): FavoriteOwner
    {
        return FavoriteOwner::fromRequest($this);
    }
}
