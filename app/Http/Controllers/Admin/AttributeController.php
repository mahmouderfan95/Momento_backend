<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Attributes\StoreRequest;
use App\Http\Requests\Admin\Attributes\UpdateRequest;
use App\Http\Resources\Admin\Attributres\AttributeCollection;
use App\Http\Resources\Admin\Attributres\AttributeResource;
use App\Services\Admin\AttributeService;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    use ApiResponseAble;
    public function __construct(protected AttributeService $service){}
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['status', 'search']);
        $perPage = (int) $request->get('per_page', 15);

        $attributes = $this->service->list($filters, $perPage);

        return $this->ApiSuccessResponse(AttributeCollection::make($attributes));
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $attribute = $this->service->store($request->validated());

        return $this->ApiSuccessResponse(new AttributeResource($attribute));
    }
    public function show(int $id): JsonResponse
    {
        $attribute = $this->service->find($id);

        return $this->ApiSuccessResponse(new AttributeResource($attribute));
    }

    public function update(UpdateRequest $request, int $id): JsonResponse
    {
        $attribute = $this->service->update($id, $request->validated());

        return $this->ApiSuccessResponse(new AttributeResource($attribute));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);

        return $this->ApiSuccessResponse([],__('messages.deleted_successfully'));
    }
}
