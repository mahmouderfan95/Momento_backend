<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Roles\StoreRequest;
use App\Http\Requests\Admin\Roles\UpdateRequest;
use App\Http\Resources\Admin\Roles\RoleCollection;
use App\Http\Resources\Admin\Roles\RoleResource;
use App\Services\Admin\RoleService;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    use ApiResponseAble;
    public function __construct(protected RoleService $service){}
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['status', 'search']);
        $perPage = (int) $request->get('per_page', 15);

        $categories = $this->service->list($filters, $perPage);

        return $this->ApiSuccessResponse(RoleCollection::make($categories));
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $role = $this->service->store($request->validated());

        return $this->ApiSuccessResponse(new RoleResource($role));
    }
    public function show(int $id): JsonResponse
    {
        $role = $this->service->find($id);

        return $this->ApiSuccessResponse(new RoleResource($role));
    }

    public function update(UpdateRequest $request, int $id): JsonResponse
    {
        $role = $this->service->update($id, $request->validated());

        return $this->ApiSuccessResponse(new RoleResource($role));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);

        return $this->ApiSuccessResponse([],__('messages.deleted_successfully'));
    }
}
