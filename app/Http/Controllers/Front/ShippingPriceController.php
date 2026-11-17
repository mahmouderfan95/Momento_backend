<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\Front\ShippingPriceService;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShippingPriceController extends Controller
{
    use ApiResponseAble;
    public function __construct(protected ShippingPriceService $shippingPriceService){}
    public function getShippingCost($cityId) : JsonResponse
    {
        $shippingCost = $this->shippingPriceService->getShippingCost($cityId);
        return $this->ApiSuccessResponse($shippingCost);
    }
}
