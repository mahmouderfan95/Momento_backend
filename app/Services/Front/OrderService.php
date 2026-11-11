<?php
namespace App\Services\Front;
use App\Enums\OrderStatusEnum;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\User;
use App\Repositories\Eloquent\Front\CartRepository;
use App\Repositories\Eloquent\Front\OrderRepository;
use App\Support\FavoriteOwner;
use App\Traits\ApiResponseAble;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    use ApiResponseAble;

    public function __construct(
        protected OrderRepository $orderRepository,
        protected CartRepository $cartRepository
    ){}
    public function listForUser(User $user, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return Order::query()
            ->with('orderItems')
            ->where('user_id', $user->id)
            ->when(
                ! empty($filters['status']),
                fn ($q) => $q->where('status', $filters['status'])
            )
            ->withCount('orderItems')
            ->latest()
            ->paginate(min($perPage, 50));
    }
    public function findForUser(User $user, string $orderNumber): Order
    {
        return Order::query()
            ->where('user_id', $user->id)
            ->where('order_number', $orderNumber)
            ->with('orderItems')
            ->firstOrFail();
    }
    public function cancel(User $user, string $orderNumber , ?string $reason) : Order
    {
        return DB::transaction(function () use ($user, $orderNumber, $reason) {
            $order = Order::query()
                ->where('user_id', $user->id)
                ->where('order_number', $orderNumber)
                ->lockForUpdate()
                ->firstOrFail();
            if (! $order->status->canBeCancelledByCustomer()) {
                throw ValidationException::withMessages([
                    'order' => ['لا يمكن إلغاء هذا الطلب في حالته الحالية'],
                ]);
            }
            $order->update([
                'status'        => OrderStatusEnum::Cancelled->value,
                'cancelled_at'  => now(),
                'cancel_reason' => $reason,
            ]);
            $order->orderItems()->update(['status' => OrderStatusEnum::Cancelled->value]);
            if ($order->coupon_id) {
                Coupon::query()
                    ->whereKey($order->coupon_id)
                    ->where('used_count', '>', 0)
                    ->decrement('used_count');
            }
            return $order->load('orderItems');
        });
    }

}
