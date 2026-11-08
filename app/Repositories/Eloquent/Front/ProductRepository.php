<?php
namespace App\Repositories\Eloquent\Front;
use App\Models\Product;
use App\Repositories\Contracts\Front\ProductRepositoryInterface;
use Illuminate\Support\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    public function findActiveOrFail(int $id, array $relations = []): Product
    {
        /** @var Product */
        return Product::query()
            ->Active()
            ->with($relations)
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->findOrFail($id);
    }

    public function getRelated(Product $product, int $limit = 10): Collection
    {
        return Product::query()
            ->Active()
            ->with('attributeOptions.attribute')
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->id)
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }
}
