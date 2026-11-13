<?php
namespace App\Services\Admin;
use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Repositories\Eloquent\OrderRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function __construct(protected OrderRepository $repository){}
    public function list(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $filters);
    }

    public function find(int $id): Order
    {
        return $this->repository->findOrFail($id);
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
    public function updateOrderStatus($id,$request) : Order
    {
        $order = $this->repository->find($id);
        $fail = fn (string $msg) => throw ValidationException::withMessages(['order_status' => [$msg]]);
        if (!$order) {
            $fail('الطلب غير موجود');
        }
        if ($order->status === OrderStatusEnum::Cancelled->value && $request->status !== OrderStatusEnum::Refunded->value) {
            $fail('لا يمكن تغيير الحالة من "ملغاة" ما لم يتم استرداد المبلغ.');
        }
        if (in_array($order->status, [OrderStatusEnum::Delivered->value, OrderStatusEnum::Refunded->value])) {
            $fail('لا يمكن تعديل الطلبات المكتملة');
        }
        $order->status = $request->status;
        $order->save();
        $order->orderItems()->update([
            'status' => $request->status
        ]);
        return $order;
    }
}
