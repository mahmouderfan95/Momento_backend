<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\Orders\CancelOrderRequest;
use App\Http\Requests\Front\Orders\StoreRequest;
use App\Http\Resources\Front\Orders\OrderCollection;
use App\Http\Resources\Front\Orders\OrderResource;
use App\Services\Front\CheckoutService;
use App\Services\Front\OrderService;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ApiResponseAble;
    public function __construct(
        protected OrderService $orderService,
        protected CheckoutService $checkoutService
    ){}
    public function index(Request $request): JsonResponse
    {
        $orders = $this->orderService->listForUser(
            $request->user(),
            $request->only('status'),
            (int) $request->get('per_page', 10)
        );
        return $this->ApiSuccessResponse(OrderCollection::make($orders));
    }
    public function show(Request $request, string $orderNumber): JsonResponse
    {
        $order = $this->orderService->findForUser($request->user(), $orderNumber);

        return $this->ApiSuccessResponse(new OrderResource($order));
    }
    public function checkout(StoreRequest $request) :JsonResponse
    {
        $order = $this->checkoutService->checkout(
            $request->owner(),
            $request->authUser(),
            $request->validated()
        );
        return $this->ApiSuccessResponse(new OrderResource($order), 'تم إنشاء الطلب بنجاح');
    }
    public function cancel(CancelOrderRequest $request, string $orderNumber): JsonResponse
    {
        $order = $this->orderService->cancel(
            $request->user(),
            $orderNumber,
            $request->validated('cancel_reason')
        );
        return $this->ApiSuccessResponse(new OrderResource($order), 'تم إلغاء الطلب بنجاح');
    }
}
