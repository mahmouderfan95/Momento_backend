<?php
namespace App\Services\Front;
use App\Repositories\Eloquent\Front\ProductRepository;

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
}
