<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\Auth\LoginRequest;
use App\Http\Requests\Front\Auth\RegisterRequest;
use App\Http\Resources\Front\Auth\UserResource;
use App\Services\Front\AuthService;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponseAble;
    public function __construct(protected AuthService $authService){}
    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register($request->validated(), $request->guestToken());

        return $this->ApiSuccessResponseAndToken(UserResource::make($result['user']), 'تم انشاء الحساب بنجاح',$result['token']);
    }
    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login($request->validated(), $request->guestToken());

        return $this->ApiSuccessResponseAndToken(UserResource::make($result['user']), 'تم تسجيل الدخول بنجاح',$result['token']);
    }
}
