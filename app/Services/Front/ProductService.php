<?php
namespace App\Services\Front;
use App\Enums\StatusEnum;
use App\Models\Product;
use App\Repositories\Eloquent\Front\ProductRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
    public function __construct(protected ProductRepository $productRepository){}
    public function details($id) : array
    {
        $product = $this->productRepository->findActiveOrFail($id, [
            'category:id,name',
            'media',
            'attributeOptions.attribute',
        ]);
        return [
            'product' => $product,
            'related' => $this->productRepository->getRelated($product),
        ];
    }
    public function getCategoryProducts($categoryId) : LengthAwarePaginator
    {
        return Product::query()->where('category_id', $categoryId)
            ->where('status',StatusEnum::ACTIVE->value)
            ->paginate(20);
    }
}
