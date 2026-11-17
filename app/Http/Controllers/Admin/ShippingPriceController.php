<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ShippingPrice\StoreRequest;
use App\Http\Requests\Admin\ShippingPrice\UpdateRequest;
use App\Http\Resources\Admin\ShippingPrice\ShippingPriceCollection;
use App\Http\Resources\Admin\ShippingPrice\ShippingPriceResource;
use App\Services\Admin\ShippingPriceService;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShippingPriceController extends Controller
{
    use ApiResponseAble;
    public function __construct(protected ShippingPriceService $service){}
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['status', 'search']);
        $perPage = (int) $request->get('per_page', 15);

        $coupons = $this->service->list($filters, $perPage);

        return $this->ApiSuccessResponse(ShippingPriceCollection::make($coupons));
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $category = $this->service->store($request->validated());

        return $this->ApiSuccessResponse(new ShippingPriceResource($category));
    }
    public function show(int $id): JsonResponse
    {
        $category = $this->service->find($id);

        return $this->ApiSuccessResponse(new ShippingPriceResource($category));
    }

    public function update(UpdateRequest $request, int $id): JsonResponse
    {
        $category = $this->service->update($id, $request->validated());

        return $this->ApiSuccessResponse(new ShippingPriceResource($category));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);

        return $this->ApiSuccessResponse([],__('messages.deleted_successfully'));
    }
}
