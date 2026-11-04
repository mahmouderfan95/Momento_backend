<?php
namespace App\Repositories\Contracts\Front;
use App\Models\Product;
use Illuminate\Support\Collection;

interface ProductRepositoryInterface
{
    public function findActiveOrFail(int $id, array $relations = []): Product;
    public function getRelated(Product $product, int $limit = 8): Collection;
}
