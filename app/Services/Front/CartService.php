<?php
namespace App\Services\Front;
use App\Models\AttributeOption;
use App\Models\Cart;
use App\Models\Product;
use App\Repositories\Eloquent\Front\CartRepository;
use App\Support\FavoriteOwner;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CartService
{
    private const MAX_ITEM_QUANTITY = 20;
    public function __construct(protected CartRepository $repository){}
    public function get(FavoriteOwner $owner): ?Cart
    {
        $cart = $this->repository->findForOwner($owner);

        return $cart?->load(['items.product', 'items.attributeOptions.attribute']);
    }
    public function add(FavoriteOwner $owner, array $data): Cart
    {
        $product   = Product::query()->Active()->findOrFail($data['product_id']);
        $optionIds = collect($data['attribute_option_ids'] ?? [])->unique()->sort()->values()->all();

        $this->assertOptionsBelongToProduct($product, $optionIds);

        $hash  = md5(implode('-', $optionIds));
        $price = $product->current_price;

        return DB::transaction(function () use ($owner, $product, $optionIds, $hash, $price, $data) {
            $cart = $this->repository->firstOrCreateForOwner($owner);
            $item = $this->repository->findItemByHash($cart, $product->id, $hash);

            if ($item) {
                $qty = $item->quantity + $data['quantity'];
                $this->assertQuantity($qty);

                $this->repository->updateItem($item, [
                    'quantity' => $qty,
                    'price'    => $price,
                    'total'    => round($price * $qty, 2),
                ]);
            } else {
                $this->assertQuantity($data['quantity']);

                $this->repository->createItem($cart, [
                    'product_id'   => $product->id,
                    'quantity'     => $data['quantity'],
                    'price'        => $price,
                    'total'        => round($price * $data['quantity'], 2),
                    'options_hash' => $hash,
                ], $optionIds);
            }

            return $this->repository->recalculate($cart)
                ->load(['items.product', 'items.attributeOptions.attribute']);
        });
    }
    public function updateQuantity(FavoriteOwner $owner, int $itemId, int $quantity): Cart
    {
        $this->assertQuantity($quantity);

        return DB::transaction(function () use ($owner, $itemId, $quantity) {
            $cart = $this->repository->findForOwner($owner) ?? abort(404);
            $item = $this->repository->findItem($cart, $itemId) ?? abort(404);

            $price = $item->product->current_price;

            $this->repository->updateItem($item, [
                'quantity' => $quantity,
                'price'    => $price,
                'total'    => round($price * $quantity, 2),
            ]);

            return $this->repository->recalculate($cart)
                ->load(['items.product', 'items.attributeOptions.attribute']);
        });
    }
    public function remove(FavoriteOwner $owner, int $itemId): Cart
    {
        return DB::transaction(function () use ($owner, $itemId) {
            $cart = $this->repository->findForOwner($owner) ?? abort(404);
            $item = $this->repository->findItem($cart, $itemId) ?? abort(404);

            $this->repository->deleteItem($item);

            return $this->repository->recalculate($cart)
                ->load(['items.product', 'items.attributeOptions.attribute']);
        });
    }

    public function clear(FavoriteOwner $owner): void
    {
        if ($cart = $this->repository->findForOwner($owner)) {
            $this->repository->clear($cart);
        }
    }
    private function assertOptionsBelongToProduct(Product $product, array $optionIds): void
    {
        if (empty($optionIds)) {
            return;
        }

        $valid = $product->attributeOptions()->whereIn('attribute_options.id', $optionIds)->count();

        if ($valid !== count($optionIds)) {
            throw ValidationException::withMessages([
                'attribute_option_ids' => ['اختيارات غير صالحة لهذا المنتج'],
            ]);
        }

        $perAttribute = AttributeOption::query()->whereIn('id', $optionIds)->pluck('attribute_id');

        if ($perAttribute->count() !== $perAttribute->unique()->count()) {
            throw ValidationException::withMessages([
                'attribute_option_ids' => ['يمكن اختيار قيمة واحدة فقط من كل خاصية'],
            ]);
        }
    }

    private function assertQuantity(int $quantity): void
    {
        if ($quantity > self::MAX_ITEM_QUANTITY) {
            throw ValidationException::withMessages([
                'quantity' => ['الحد الأقصى للكمية ' . self::MAX_ITEM_QUANTITY],
            ]);
        }
    }
}
