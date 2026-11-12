<?php
namespace App\Services\Front;
use App\Models\Order;
use App\Models\Product;

class StockService
{
    public function reverse(Order $order) : void
    {
        $items = $order->orderItems->sortBy('product_id');
        foreach ($items as $item) {
            Product::query()
                ->whereKey($item->product_id)
                ->where('available_quantity', '>=', $item->quantity)
                ->decrement('available_quantity', $item->quantity);
        }
    }
    public function release(Order $order): void
    {
        foreach ($order->orderItems() as $item) {
            if ($item->product_id) {
                Product::query()
                    ->whereKey($item->product_id)
                    ->increment('available_quantity', $item->quantity);
            }
        }
    }
}
