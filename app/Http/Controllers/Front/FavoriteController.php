<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\Favorites\FavoriteRequest;
use App\Http\Resources\Front\Products\ProductCollection;
use App\Services\Front\FavoriteService;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    use ApiResponseAble;
    public function __construct(protected FavoriteService $favoriteService){}
    public function index(FavoriteRequest $request) : JsonResponse
    {
        $fav = $this->favoriteService->list($request->owner(),15);
        return $this->ApiSuccessResponse(ProductCollection::make($fav));
    }
    public function toggle(FavoriteRequest $request,int $productId) : JsonResponse
    {
        $result = $this->favoriteService->toggle($request->owner(),$productId);
        return $this->ApiSuccessResponse($result,$result['is_favorite'] ? 'تمت الإضافة إلى المفضلة' : 'تمت الإزالة من المفضلة');
    }
}
