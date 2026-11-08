<?php
namespace App\Services\Front;
use App\Models\Cart;
use App\Models\User;
use App\Models\UserProductWhitelist;
use Illuminate\Support\Facades\DB;

class GuestDataMerger
{
    public function merge(User $user, ?string $guestToken): void
    {
        if (! $guestToken) {
            return;
        }

        DB::transaction(function () use ($user, $guestToken) {
            $this->mergeFavorites($user, $guestToken);
            $this->mergeCart($user, $guestToken);
        });
    }
    private function mergeFavorites(User $user, string $guestToken): void
    {
        $guestItems = UserProductWhitelist::query()
            ->whereNull('user_id')
            ->where('guest_token', $guestToken)
            ->get();

        if ($guestItems->isEmpty()) {
            return;
        }

        $existing = UserProductWhitelist::query()->where('user_id', $user->id)
            ->pluck('product_id')
            ->all();

        foreach ($guestItems as $item) {
            if (in_array($item->product_id, $existing, true)) {
                $item->delete();
            } else {
                $item->update(['user_id' => $user->id, 'guest_token' => null]);
            }
        }
    }
    private function mergeCart(User $user, string $guestToken): void
    {
        $guestCart = Cart::query()
            ->whereNull('user_id')
            ->where('guest_token', $guestToken)
            ->with('items.attributeOptions')
            ->lockForUpdate()
            ->first();

        if (! $guestCart) {
            return;
        }
        $userCart = Cart::query()->where('user_id', $user->id)->lockForUpdate()->first();
        if (! $userCart) {
            $guestCart->update(['user_id' => $user->id, 'guest_token' => null]);

            return;
        }
        foreach ($guestCart->items as $guestItem) {
            $match = $userCart->items()
                ->where('product_id', $guestItem->product_id)
                ->where('options_hash', $guestItem->options_hash)
                ->first();

            if ($match) {
                $qty = min($match->quantity + $guestItem->quantity, 20);

                $match->update([
                    'quantity' => $qty,
                    'total'    => round($match->price * $qty, 2),
                ]);
            } else {
                $guestItem->update(['cart_id' => $userCart->id]);
            }
        }
        $userCart->update(['total' => $userCart->items()->sum('total')]);

        $guestCart->delete();
    }
}
