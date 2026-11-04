<?php
namespace App\Services\Front;
use App\Http\Resources\Front\HomePage\HomeCategoryResource;
use App\Http\Resources\Front\HomePage\HomeProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;

class HomePageService
{
    private const LIMIT = 10;
    private const CACHE_TTL = 600;
    public function getHomeData(): array
    {
        return [
            'categories'      => HomeCategoryResource::collection($this->categories()),
            'latest_products' => HomeProductResource::collection($this->latestProducts()),
            'offers'          => HomeProductResource::collection($this->offers()),
//            'best_sellers'    => $this->bestSellers(),
        ];
    }
    public function categories(): Collection
    {
        return Category::query()
            ->Active()
            ->withCount(['products' => fn ($q) => $q->Active()])
            ->latest()
            ->get();
    }
    public function latestProducts(): Collection
    {
        return Product::query()
            ->Active()
            ->with(['attributeOptions.attribute'])
            ->latest()
            ->limit(self::LIMIT)
            ->get();
    }
    public function offers(): Collection
    {
        return Product::query()
            ->Active()
            ->OnOffer()
            ->with(['attributeOptions.attribute'])
            ->orderBy('discount_ends_at')
            ->limit(self::LIMIT)
            ->get();
    }
}
