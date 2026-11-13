<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Coupons\CouponResource;
use App\Services\Front\CouponService;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    use ApiResponseAble;
    public function __construct(protected CouponService $couponService){}
    public function getValidCoupon($code) : JsonResponse
    {
        $coupon = $this->couponService->getValidCoupon($code);
        if(!$coupon)
            return $this->notFoundResponse();
        return $this->ApiSuccessResponse(CouponResource::make($coupon));
    }
    public function getCouponValue($code,$total) : JsonResponse
    {
        $coupon = $this->couponService->getCouponValue($code,$total);
        return $this->ApiSuccessResponse($coupon->value);
    }
}
