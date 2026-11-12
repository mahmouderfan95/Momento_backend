<?php
namespace App\Services\Front;
use App\Enums\OrderStatusEnum;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\User;
use App\Services\Admin\ImageService;
use App\Support\FavoriteOwner;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CheckoutService
{
    public function __construct(
        protected CouponService $couponService,
        protected GuestDataMerger$merger,
        protected StockService $stockService,
        protected ImageService $imageService,
    ){}
    public function checkout(FavoriteOwner $owner, ?User $authUser ,array $data) : Order
    {
        DB::beginTransaction();
        $user = $authUser ?? $this->createCustomerFromGuest($data);
        if (! $authUser) {
            $this->merger->merge($user, $owner->guestToken);
        }
        $cart = Cart::query()
            ->where('user_id', $user->id)
            ->lockForUpdate()
            ->first();

        if (! $cart || $cart->items()->doesntExist()) {
            throw ValidationException::withMessages(['cart' => ['السلة فارغة']]);
        }

        $cart->load(['items.product', 'items.attributeOptions.attribute']);

        $subtotal   = 0;
        $orderItems = [];

        foreach ($cart->items as $item) {
            $product = $item->product;
            if (! $product || $product->status !== 'active') {
                throw ValidationException::withMessages([
                    'cart' => ["المنتج «{$item->product?->name}» غير متاح حاليًا"],
                ]);
            }
            $price = $product->current_price;
            $line  = round($price * $item->quantity, 2);
            $subtotal += $line;
            $orderItems[] = [
                'product_id'       => $product->id,
                'quantity'         => $item->quantity,
                'price'            => $price,
                'total'            => $line,
                'product_snapshot' => [
                    'name'    => $product->getTranslations('name'),
                    'image'   => $product->image,
                    'options' => $item->attributeOptions->map(fn ($o) => [
                        'attribute' => $o->attribute->getTranslations('name'),
                        'name'      => $o->getTranslations('name'),
                        'value'     => $o->value,
                    ])->all(),
                ],
            ];
        }
        $coupon   = null;
        $discount = 0;
        $imagePath = null;

        if (! empty($data['coupon_code'])) {
            [$coupon, $discount] = $this->couponService->validateAndCalculate(
                $data['coupon_code'], $user, $subtotal
            );
        }
        if(isset($data['conversion_image'])){
            $imagePath = $this->imageService->store($data['conversion_image'], 'orders');
            $data['conversion_image'] = $imagePath;
        }

        $total = max(round($subtotal - $discount, 2), 0);

        $order = Order::query()->create([
            'order_number'   => $this->generateOrderNumber(),
            'user_id'        => $user->id,
            'coupon_id'      => $coupon?->id,
            'status'         => OrderStatusEnum::Pending->value,
            'sub_total'       => $subtotal,
            'discount'       => $discount,
            'total'          => $total,
            'payment_type'   => $data['payment_type'],
            'customer_name'  => $data['customer_name']  ?? $user->name,
            'customer_phone' => $data['customer_phone'] ?? $user->phone,
            'customer_email' => $user->email,
            'address'        => $data['address'],
            'notes'          => $data['notes'] ?? null,
            'conversion_image' => $imagePath,
            'wallet_number' => $data['wallet_number'],
            'wallet_provider_id' => $data['wallet_provider_id'],
        ]);
        $order->orderItems()->createMany($orderItems);

        if ($coupon) {
            $this->couponService->consume($coupon, $user, $order);
        }

        $cart->items()->delete();
        $cart->delete();
        $order->load('orderItems');
        $this->stockService->reverse($order);
        DB::commit();
        return $order;
    }
    private function generateOrderNumber(): string
    {
        do {
            $number = 'MOM-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
        } while (Order::query()->where('order_number', $number)->exists());

        return $number;
    }
    private function createCustomerFromGuest(array $data): User
    {
        $user = User::query()
            ->where('phone', $data['phone'])
            ->first();

        if ($user) {
            return $user;
        }

        return User::query()->create([
            'name'     => $data['name'],
            'phone'    => $data['phone'],
            'email'    => $data['email'] ?? null,
            'password' => Hash::make('10101010'),
        ]);
    }
}
