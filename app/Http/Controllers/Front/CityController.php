<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Cities\CityResource;
use App\Http\Resources\Admin\Regions\RegionResource;
use App\Services\Front\CityService;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CityController extends Controller
{
    use ApiResponseAble;
    public function __construct(protected CityService $cityService){}
    public function getCities() : JsonResponse
    {
        $cities = $this->cityService->getCities();
        return $this->ApiSuccessResponse(CityResource::collection($cities));
    }
    public function getRegions($cityId) : JsonResponse
    {
        $regions = $this->cityService->getRegionByCityId($cityId);
        return $this->ApiSuccessResponse(RegionResource::collection($regions));
    }
}
