<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\SettingResource;
use App\Services\Front\SettingService;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    use ApiResponseAble;
    public function __construct(protected SettingService $settingService){}
    public function index() : JsonResponse
    {
        $setting = $this->settingService->getSetting();
        return $this->ApiSuccessResponse(SettingResource::make($setting));
    }
}
