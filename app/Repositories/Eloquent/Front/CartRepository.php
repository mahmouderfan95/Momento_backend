<?php
namespace App\Repositories\Eloquent\Front;
use App\Models\Cart;
use App\Models\CartItem;
use App\Repositories\Contracts\Front\CartRepositoryInterface;
use App\Support\FavoriteOwner;

class CartRepository implements CartRepositoryInterface
{
    public function findForOwner(FavoriteOwner $owner): ?Cart
    {
        return Cart::query()
            ->when($owner->userId,
                fn ($q) => $q->where('user_id', $owner->userId),
                fn ($q) => $q->whereNull('user_id')->where('guest_token', $owner->guestToken))
            ->first();
    }
    public function firstOrCreateForOwner(FavoriteOwner $owner): Cart
    {
        return Cart::query()->firstOrCreate([
            'user_id'     => $owner->userId,
            'guest_token' => $owner->userId ? null : $owner->guestToken,
        ]);
    }
    public function findItem(Cart $cart, int $itemId): ?CartItem
    {
        return $cart->items()->whereKey($itemId)->first();
    }
    public function findItemByHash(Cart $cart, int $productId, string $hash): ?CartItem
    {
        return $cart->items()
            ->where('product_id', $productId)
            ->where('options_hash', $hash)
            ->lockForUpdate()
            ->first();
    }
    public function createItem(Cart $cart, array $data, array $optionIds): CartItem
    {
        $item = $cart->items()->create($data);
        $item->attributeOptions()->sync($optionIds);

        return $item;
    }

    public function updateItem(CartItem $item, array $data): CartItem
    {
        $item->update($data);

        return $item;
    }
    public function deleteItem(CartItem $item): void
    {
        $item->delete();
    }

    public function clear(Cart $cart): void
    {
        $cart->items()->delete();
        $cart->update(['total' => 0]);
    }

    public function recalculate(Cart $cart): Cart
    {
        $cart->update(['total' => $cart->items()->sum('total')]);

        return $cart;
    }
}
