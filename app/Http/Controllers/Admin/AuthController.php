<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\AuthenticationFailedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Front\Auth\LoginRequest;
use App\Http\Resources\Front\Auth\UserResource;
use App\Services\Admin\AuthService;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    use ApiResponseAble;
    public function __construct(protected AuthService $authService){}
    public function login(LoginRequest $request) : JsonResponse
    {
        try{
            $result = $this->authService->login($request->validated());
            return $this->ApiSuccessResponseAndToken(UserResource::make($result['user']),'user login successfully',$result['token']);
        }catch (AuthenticationFailedException $exception){
            Log::error('error for user login ' . $exception->getMessage());
            return $this->ApiErrorResponse([],$exception->getMessage(),401);
        }
    }
    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return $this->ApiSuccessResponse([], 'User logged out successfully');
    }
}
