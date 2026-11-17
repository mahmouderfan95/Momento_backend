<?php
namespace App\Services\Admin;
use App\Enums\OrderStatusEnum;
use App\Models\Coupon;
use App\Models\Order;
use App\Notifications\OrderStatusChangedNotification;
use App\Repositories\Eloquent\OrderRepository;
use App\Services\Front\StockService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function __construct(
        protected OrderRepository $repository,
        protected StockService $stockService,
    ){}
    public function list(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $filters);
    }

    public function find(int $id): Order
    {
        return $this->repository->findOrFail($id,['orderItems']);
    }

    public function store(array $data): Order
    {
        try{
            $imagePath = null;
            if(isset($data['image'])){
                $imagePath = $this->imageService->store($data['image'], 'categories');
                $data['image'] = $imagePath;
            }

            return DB::transaction(fn () => $this->repository->create($data));
        }catch (\Exception $exception){
            Log::error('error for store category ' . $exception->getMessage());
        }
    }

    public function update(int $id, array $data): Order
    {
        try{
            $category = $this->repository->findOrFail($id);

            $oldImage = $category->image;

            $newImage = null;

            if (isset($data['image'])) {
                $newImage = $this->imageService->store($data['image'], 'categories');
                $data['image'] = $newImage;
            }

            $updateCategory =  DB::transaction(fn () => $this->repository->update($category, $data));

            if ($newImage) {
                $this->imageService->delete($oldImage,'categories');
            }
            return $updateCategory;
        }catch (\Exception $exception){
            Log::error('error for update category ' . $exception->getMessage());
        }
    }

    public function delete(int $id): bool
    {
        $order = $this->repository->findOrFail($id);

        $imagePath = $order->image;

        $deleteOrder = $this->repository->delete($order);

        if ($deleteOrder)
            $this->imageService->delete($imagePath,'orders');

        return $deleteOrder;
    }
    public function updateOrderStatus(
        int $id,
        OrderStatusEnum $newStatus,
        ?string $cancelReason = null
    ): Order {
        return DB::transaction(function () use ($id, $newStatus, $cancelReason) {

            /** @var Order $order */
            $order   = Order::query()->lockForUpdate()->findOrFail($id);
            $current = $order->status;

            if ($current === $newStatus) {
                throw ValidationException::withMessages([
                    'status' => ['الطلب بالفعل في هذه الحالة'],
                ]);
            }

            if (! $current->canTransitionTo($newStatus)) {
                throw ValidationException::withMessages([
                    'status' => ["لا يمكن تحويل الطلب من «{$current->label()}» إلى «{$newStatus->label()}»"],
                ]);
            }

            $attributes = ['status' => $newStatus];

            if ($newStatus === OrderStatusEnum::Cancelled) {
                $attributes['cancelled_at']  = now();
                $attributes['cancel_reason'] = $cancelReason;
            }

            $order->update($attributes);
            $order->orderItems()->update(['status' => $newStatus->value]);

            $releasesStock = in_array($newStatus, [OrderStatusEnum::Cancelled, OrderStatusEnum::Refunded], true)
                && $current !== OrderStatusEnum::Cancelled;

            if ($releasesStock) {
                $order->load('orderItems');
                $this->stockService->release($order);

                if ($order->coupon_id) {
                    Coupon::query()->whereKey($order->coupon_id)
                        ->where('used_count', '>', 0)
                        ->decrement('used_count');
                }
            }

            $order->user?->notify(new OrderStatusChangedNotification($order, $newStatus));

            return $order->refresh();
        });
    }
}
