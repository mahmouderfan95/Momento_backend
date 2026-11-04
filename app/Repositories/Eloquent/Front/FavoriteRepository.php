<?php
namespace App\Repositories\Eloquent\Front;
use App\Models\Product;
use App\Models\UserProductWhitelist;
use App\Repositories\Contracts\Front\FavoriteRepositoryInterface;
use App\Support\FavoriteOwner;
use Illuminate\Pagination\LengthAwarePaginator;

class FavoriteRepository implements FavoriteRepositoryInterface
{
    public function exists(FavoriteOwner $owner, int $productId): bool
    {
        return UserProductWhitelist::query()
            ->forOwner($owner->userId, $owner->guestToken)
            ->where('product_id', $productId)
            ->exists();
    }
    public function add(FavoriteOwner $owner, int $productId): void
    {
        UserProductWhitelist::query()->firstOrCreate([
            'user_id'     => $owner->userId,
            'guest_token' => $owner->userId ? null : $owner->guestToken,
            'product_id'  => $productId,
        ]);
    }
    public function remove(FavoriteOwner $owner, int $productId): int
    {
        return UserProductWhitelist::query()
            ->forOwner($owner->userId, $owner->guestToken)
            ->where('product_id', $productId)
            ->delete();
    }
    public function paginateProducts(FavoriteOwner $owner, int $perPage = 12): LengthAwarePaginator
    {
        return Product::query()
            ->Active()
            ->with('attributeOptions.attribute')
            ->whereIn('id', UserProductWhitelist::query()
                ->forOwner($owner->userId, $owner->guestToken)
                ->select('product_id'))
            ->latest()
            ->paginate($perPage);
    }

    public function count(FavoriteOwner $owner): int
    {
        return UserProductWhitelist::query()
            ->forOwner($owner->userId, $owner->guestToken)
            ->count();
    }
}
