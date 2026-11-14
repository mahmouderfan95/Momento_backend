<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\UpdateRequest;
use App\Http\Resources\Admin\SettingResource;
use App\Services\Admin\SettingService;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    use ApiResponseAble;
    public function __construct(protected SettingService $settingService){}
    public function show() : JsonResponse
    {
        $setting = $this->settingService->get();
        return $this->ApiSuccessResponse(SettingResource::make($setting));
    }
    public function update(UpdateRequest $request) : JsonResponse
    {
        $setting = $this->settingService->update($request->validated());
        return $this->ApiSuccessResponse(SettingResource::make($setting));
    }
}
