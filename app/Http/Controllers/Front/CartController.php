<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\Carts\AddToCartRequest;
use App\Http\Requests\Front\Carts\UpdateCartItemRequest;
use App\Http\Requests\Front\Favorites\FavoriteRequest;
use App\Http\Resources\Front\Carts\CartResource;
use App\Services\Front\CartService;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use ApiResponseAble;
    public function __construct(protected CartService $service){}
    public function show(FavoriteRequest $request): JsonResponse
    {
        $cart = $this->service->get($request->owner());

        return $this->ApiSuccessResponse(
            $cart ? new CartResource($cart) : ['items' => [], 'items_count' => 0, 'total' => 0]
        );
    }

    public function store(AddToCartRequest $request): JsonResponse
    {
        $cart = $this->service->add($request->owner(), $request->validated());

        return $this->ApiSuccessResponse(new CartResource($cart), 'تمت الإضافة إلى السلة');
    }
    public function update(UpdateCartItemRequest $request, int $itemId): JsonResponse
    {
        $cart = $this->service->updateQuantity($request->owner(), $itemId, $request->validated('quantity'));

        return $this->ApiSuccessResponse(new CartResource($cart));
    }
    public function destroy(FavoriteRequest $request, int $itemId): JsonResponse
    {
        $cart = $this->service->remove($request->owner(), $itemId);

        return $this->ApiSuccessResponse(new CartResource($cart), 'تم الحذف من السلة');
    }

    public function clear(FavoriteRequest $request): JsonResponse
    {
        $this->service->clear($request->owner());

        return $this->ApiSuccessResponse(null, 'تم تفريغ السلة');
    }
}
