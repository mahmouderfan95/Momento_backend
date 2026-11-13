<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\Front\WalletProviderService;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WalletProviderController extends Controller
{
    use ApiResponseAble;
    public function __construct(protected WalletProviderService $walletProviderService){}
    public function index() : JsonResponse
    {
        $providers = $this->walletProviderService->getWalletProviders();
        return $this->ApiSuccessResponse($providers,'Providers list');
    }
    public function getWalletNumbers($providerId) : JsonResponse
    {
        $numbers = $this->walletProviderService->getWalletNumbersByProviderId($providerId);
        return $this->ApiSuccessResponse($numbers,'Numbers list');
    }
}
