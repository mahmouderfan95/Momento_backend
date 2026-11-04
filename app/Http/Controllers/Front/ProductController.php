<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Resources\Front\Products\ProductDetailResource;
use App\Services\Front\ProductService;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiResponseAble;
    public function __construct(protected ProductService $service){}
    public function details(int $id) : JsonResponse
    {
        $data = $this->service->details($id);

        return $this->ApiSuccessResponse([
            'product' => new ProductDetailResource($data['product']),
            'related' => ProductDetailResource::collection($data['related']),
        ]);
    }
}
