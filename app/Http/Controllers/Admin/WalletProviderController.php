<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WalletProviders\StoreRequest;
use App\Http\Requests\Admin\WalletProviders\UpdateRequest;
use App\Http\Resources\Admin\WalletProviders\WalletProviderCollection;
use App\Http\Resources\Admin\WalletProviders\WalletProviderResource;
use App\Services\Admin\WalletProviderService;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WalletProviderController extends Controller
{
    use ApiResponseAble;
    public function __construct(protected WalletProviderService $service){}
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['status', 'search']);
        $perPage = (int) $request->get('per_page', 15);

        $coupons = $this->service->list($filters, $perPage);

        return $this->ApiSuccessResponse(WalletProviderCollection::make($coupons));
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $category = $this->service->store($request->validated());

        return $this->ApiSuccessResponse(new WalletProviderResource($category));
    }
    public function show(int $id): JsonResponse
    {
        $category = $this->service->find($id);

        return $this->ApiSuccessResponse(new WalletProviderResource($category));
    }

    public function update(UpdateRequest $request, int $id): JsonResponse
    {
        $category = $this->service->update($id, $request->validated());

        return $this->ApiSuccessResponse(new WalletProviderResource($category));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);

        return $this->ApiSuccessResponse([],__('messages.deleted_successfully'));
    }
}
