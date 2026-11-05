<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Sliders\StoreRequest;
use App\Http\Requests\Admin\Sliders\UpdateRequest;
use App\Http\Resources\Admin\Sliders\SliderCollection;
use App\Http\Resources\Admin\Sliders\SliderResource;
use App\Services\Admin\SliderService;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    use ApiResponseAble;
    public function __construct(protected SliderService $service){}
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['status', 'search']);
        $perPage = (int) $request->get('per_page', 15);

        $sliders = $this->service->list($filters, $perPage);

        return $this->ApiSuccessResponse(SliderCollection::make($sliders));
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $slider = $this->service->store($request->validated());

        return $this->ApiSuccessResponse(new SliderResource($slider));
    }
    public function show(int $id): JsonResponse
    {
        $slider = $this->service->find($id);

        return $this->ApiSuccessResponse(new SliderResource($slider));
    }

    public function update(UpdateRequest $request, int $id): JsonResponse
    {
        $category = $this->service->update($id, $request->validated());

        return $this->ApiSuccessResponse(new SliderResource($category));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);

        return $this->ApiSuccessResponse([],__('messages.deleted_successfully'));
    }
}
