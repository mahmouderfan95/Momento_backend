<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AttributeOption\StoreRequest;
use App\Http\Requests\Admin\AttributeOption\UpdateRequest;
use App\Http\Resources\Admin\AttributreOption\AttributeOptionCollection;
use App\Http\Resources\Admin\AttributreOption\AttributeOptionResource;
use App\Services\Admin\AttributeOptionService;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttributeOptionController extends Controller
{
    use ApiResponseAble;
    public function __construct(protected AttributeOptionService $service){}
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['status', 'search']);
        $perPage = (int) $request->get('per_page', 15);

        $attributes = $this->service->list($filters, $perPage);

        return $this->ApiSuccessResponse(AttributeOptionCollection::make($attributes));
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $attribute = $this->service->store($request->validated());

        return $this->ApiSuccessResponse(new AttributeOptionResource($attribute));
    }
    public function show(int $id): JsonResponse
    {
        $attribute = $this->service->find($id);

        return $this->ApiSuccessResponse(new AttributeOptionResource($attribute));
    }

    public function update(UpdateRequest $request, int $id): JsonResponse
    {
        $attribute = $this->service->update($id, $request->validated());

        return $this->ApiSuccessResponse(new AttributeOptionResource($attribute));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);

        return $this->ApiSuccessResponse([],__('messages.deleted_successfully'));
    }
}
