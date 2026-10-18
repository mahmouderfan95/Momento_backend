<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Products\StoreRequest;
use App\Http\Requests\Admin\Products\UpdateRequest;
use App\Http\Resources\Admin\Products\ProductCollection;
use App\Http\Resources\Admin\Products\ProductResource;
use App\Services\Admin\ProductService;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiResponseAble;
    public function __construct(protected ProductService $service){}
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['status', 'search']);
        $perPage = (int) $request->get('per_page', 15);

        $attributes = $this->service->list($filters, $perPage);

        return $this->ApiSuccessResponse(ProductCollection::make($attributes));
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $attribute = $this->service->store($request->validated());

        return $this->ApiSuccessResponse(new ProductResource($attribute));
    }
    public function show(int $id): JsonResponse
    {
        $attribute = $this->service->find($id);

        return $this->ApiSuccessResponse(new ProductResource($attribute));
    }

    public function update(UpdateRequest $request, int $id): JsonResponse
    {
        $attribute = $this->service->update($id, $request->validated());

        return $this->ApiSuccessResponse(new ProductResource($attribute));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);

        return $this->ApiSuccessResponse([],__('messages.deleted_successfully'));
    }
}
