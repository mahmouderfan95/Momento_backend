<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\Ratings\StoreRequest;
use App\Http\Resources\Front\Ratings\RatingResource;
use App\Services\Front\RatingService;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    use ApiResponseAble;
    public function __construct(protected RatingService $service){}
    public function store(StoreRequest $request, int $productId): JsonResponse
    {
        $rating = $this->service->store($request->user(), $productId, $request->validated());

        return $this->ApiSuccessResponse(RatingResource::make($rating),'تم حفظ التقييم');
    }
}
