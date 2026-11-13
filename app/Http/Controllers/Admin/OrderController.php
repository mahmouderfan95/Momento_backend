<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Orders\UpdateStatusRequest;
use App\Http\Resources\Admin\Orders\OrderCollection;
use App\Http\Resources\Admin\Orders\OrderResource;
use App\Services\Admin\OrderService;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ApiResponseAble;
    public function __construct(protected OrderService $service){}
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['status', 'search']);
        $perPage = (int) $request->get('per_page', 15);

        $categories = $this->service->list($filters, $perPage);

        return $this->ApiSuccessResponse(OrderCollection::make($categories));
    }

//    public function store(StoreRequest $request): JsonResponse
//    {
//        $category = $this->service->store($request->validated());
//
//        return $this->ApiSuccessResponse(new CategoryResource($category));
//    }
    public function show(int $id): JsonResponse
    {
        $order = $this->service->find($id);

        return $this->ApiSuccessResponse(new OrderResource($order));
    }

//    public function update(UpdateRequest $request, int $id): JsonResponse
//    {
//        $category = $this->service->update($id, $request->validated());
//
//        return $this->ApiSuccessResponse(new CategoryResource($category));
//    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);

        return $this->ApiSuccessResponse([],__('messages.deleted_successfully'));
    }
    public function updateStatus(int $id, UpdateStatusRequest $request): JsonResponse
    {
        $order = $this->service->updateOrderStatus($id, $request);
        return $this->ApiSuccessResponse([],'order status updated successfully');
    }
}
