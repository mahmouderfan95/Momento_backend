<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;;
use App\Http\Requests\Admin\ShippingCompanies\StoreRequest;
use App\Http\Requests\Admin\ShippingCompanies\UpdateRequest;
use App\Http\Resources\Admin\ShippingCompany\ShippingCompanyCollection;
use App\Http\Resources\Admin\ShippingCompany\ShippingCompanyResource;
use App\Services\Admin\ShippingCompanyService;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShippingCompanyController extends Controller
{
    use ApiResponseAble;
    public function __construct(protected ShippingCompanyService $service){}
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['status', 'search']);
        $perPage = (int) $request->get('per_page', 15);

        $coupons = $this->service->list($filters, $perPage);

        return $this->ApiSuccessResponse(ShippingCompanyCollection::make($coupons));
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $category = $this->service->store($request->validated());

        return $this->ApiSuccessResponse(new ShippingCompanyResource($category));
    }
    public function show(int $id): JsonResponse
    {
        $category = $this->service->find($id);

        return $this->ApiSuccessResponse(new ShippingCompanyResource($category));
    }

    public function update(UpdateRequest $request, int $id): JsonResponse
    {
        $category = $this->service->update($id, $request->validated());

        return $this->ApiSuccessResponse(new ShippingCompanyResource($category));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);

        return $this->ApiSuccessResponse([],__('messages.deleted_successfully'));
    }
}
