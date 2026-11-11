<?php

namespace App\Http\Requests\Front\Orders;

use App\Models\User;
use App\Support\FavoriteOwner;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->owner()->isIdentified();
    }

    protected function failedAuthorization(): void
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'يجب إرسال توكن الدخول أو X-Guest-Token',
        ], 401));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'notes' => ['nullable', 'string'],
            'payment_type' => ['required', 'string', 'in:cod,visa,wallet'],
            'address' => ['required', 'string', 'max:255'],
            'conversion_image' => ['required_if:payment_type,wallet', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'coupon_code' => ['nullable', 'string', 'exists:coupons,code'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string','regex:/^01[0-2,5][0-9]{8}$/',],
            'email' => ['nullable', 'string', 'email', 'max:255'],
        ];
    }
    public function owner(): FavoriteOwner
    {
        return FavoriteOwner::fromRequest($this);
    }
    public function authUser(): ?User
    {
        $user = $this->user('web');

        return $user instanceof User ? $user : null;
    }
}
