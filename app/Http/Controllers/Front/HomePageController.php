<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\Front\HomePageService;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomePageController extends Controller
{
    use ApiResponseAble;
    public function __construct(protected HomePageService $homePageService){}
    public function index() : JsonResponse
    {
        $data = $this->homePageService->getHomeData();
        return $this->ApiSuccessResponse($data);
    }
}
