<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Categories\StoreRequest;
use App\Http\Requests\Admin\Categories\UpdateRequest;
use App\Http\Resources\Admin\Categories\CategoryCollection;
use App\Http\Resources\Admin\Categories\CategoryResource;
use App\Services\Admin\CategoryService;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponseAble;
    public function __construct(protected CategoryService $service){}
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['status', 'search']);
        $perPage = (int) $request->get('per_page', 15);

        $categories = $this->service->list($filters, $perPage);

        return $this->ApiSuccessResponse(CategoryCollection::make($categories));
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $category = $this->service->store($request->validated());

        return $this->ApiSuccessResponse(new CategoryResource($category));
    }
    public function show(int $id): JsonResponse
    {
        $category = $this->service->find($id);

        return $this->ApiSuccessResponse(new CategoryResource($category));
    }

    public function update(UpdateRequest $request, int $id): JsonResponse
    {
        $category = $this->service->update($id, $request->validated());

        return $this->ApiSuccessResponse(new CategoryResource($category));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);

        return $this->ApiSuccessResponse([],__('messages.deleted_successfully'));
    }
}
