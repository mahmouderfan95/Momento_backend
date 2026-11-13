<?php
namespace App\Services\Front;
use App\Enums\OrderStatusEnum;
use App\Enums\StatusEnum;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class CouponService
{
    public function getValidCoupon($code) : ?Coupon
    {
        return Coupon::query()
            ->where('code', $code)
            ->where('status', StatusEnum::ACTIVE->value)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->lockForUpdate()
            ->first();
    }
    public function getCouponValue($code,$subTotal) : ?Coupon
    {
        $fail = fn (string $msg) => throw ValidationException::withMessages(['coupon_code' => [$msg]]);
        $coupon = $this->getValidCoupon($code);
        if (!$coupon) {
            $fail('الكوبون منتهى الصلاحية');
        }

        // Check usage limit
        if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
            $fail('تم تجاوز الحد الاقصى من الكوبون');
        }

        // Check minimum order total
        if ($coupon->min_order_total && $subTotal < $coupon->min_order_total) {
            $fail('اجمالى الطلب اقل من الحد الادنى المطلوب لاستخدام للكوبون');
        }

        return $coupon;
    }
    public function validateAndCalculate(string $code, User $user, float $subtotal): array
    {
        $coupon = Coupon::query()
            ->where('code', strtoupper(trim($code)))
            ->lockForUpdate()
            ->first();
        $fail = fn (string $msg) => throw ValidationException::withMessages(['coupon_code' => [$msg]]);
        if (! $coupon || $coupon->status !== 'active') {
            $fail('كود الخصم غير صالح');
        }
        if ($coupon->starts_at && $coupon->starts_at->isFuture()) {
            $fail('كود الخصم لم يبدأ بعد');
        }
        if ($coupon->expires_at && $coupon->expires_at->isPast()) {
            $fail('كود الخصم منتهي');
        }
        if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
            $fail('تم استنفاد كود الخصم');
        }
        if ($coupon->min_order_amount && $subtotal < $coupon->min_order_amount) {
            $fail("الحد الأدنى للطلب {$coupon->min_order_amount}");
        }
        $discount = round(min((float) $coupon->value, $subtotal), 2);

        return [$coupon, $discount];
    }
    public function consume(Coupon $coupon, User $user, Order $order): void
    {
        $coupon->increment('used_count');
    }
}
