<?php
namespace App\Repositories\Contracts\Front;
use App\Models\Cart;
use App\Models\CartItem;
use App\Support\FavoriteOwner;

interface CartRepositoryInterface
{
    public function findForOwner(FavoriteOwner $owner): ?Cart;
    public function firstOrCreateForOwner(FavoriteOwner $owner): Cart;
    public function findItem(Cart $cart, int $itemId): ?CartItem;
    public function findItemByHash(Cart $cart, int $productId, string $hash): ?CartItem;
    public function createItem(Cart $cart, array $data, array $optionIds): CartItem;
    public function updateItem(CartItem $item, array $data): CartItem;
    public function deleteItem(CartItem $item): void;
    public function clear(Cart $cart): void;
    public function recalculate(Cart $cart): Cart;
}
