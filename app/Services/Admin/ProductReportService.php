<?php
namespace App\Services\Admin;
use App\Enums\OrderStatusEnum;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ProductReportService
{
    public function topSelling(Carbon $from, Carbon $to, int $limit): array
    {
        return OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereIn('orders.status', [OrderStatusEnum::Pending,OrderStatusEnum::Completed, OrderStatusEnum::Shipped, OrderStatusEnum::Delivered])
            ->whereBetween('orders.created_at', [$from, $to])
            ->whereNotNull('order_items.product_id')
            ->select('order_items.product_id')
            ->selectRaw('SUM(order_items.quantity) as units_sold, SUM(order_items.total) as revenue')
            ->groupBy('order_items.product_id')
            ->orderByDesc('units_sold')
            ->limit($limit)
            ->get()
            ->map(function ($r) {
                $product = Product::query()->find($r->product_id);

                return [
                    'product_id' => $r->product_id,
                    'name'       => $product?->getTranslations('name'),
                    'image'      => $product?->image_url,
                    'units_sold' => (int) $r->units_sold,
                    'revenue'    => (float) $r->revenue,
                ];
            })->all();
    }
    public function stock(int $lowThreshold = 5): array
    {
        $base = Product::query();

        return [
            'out_of_stock_count' => (clone $base)->where('available_quantity', 0)->count(),
            'low_stock_count'    => (clone $base)->whereBetween('available_quantity', [1, $lowThreshold])->count(),
            'stock_value'        => (float) (clone $base)->sum(DB::raw('available_quantity * price')),
            'low_stock_products' => (clone $base)
                ->where('available_quantity', '<=', $lowThreshold)
                ->orderBy('available_quantity')
                ->limit(20)
                ->get(['id', 'name', 'available_quantity'])
                ->map(fn ($p) => [
                    'id'       => $p->id,
                    'name'     => $p->getTranslations('name'),
                    'quantity' => $p->available_quantity,
                ]),
        ];
    }
    public function deadStock(Carbon $from, Carbon $to, int $limit): array
    {
        return Product::active()
            ->where('available_quantity', '>', 0)
            ->whereNotIn('id', function ($q) use ($from, $to) {
                $q->select('order_items.product_id')
                    ->from('order_items')
                    ->join('orders', 'orders.id', '=', 'order_items.order_id')
                    ->whereIn('orders.status', ['completed', 'shipped', 'delivered'])
                    ->whereBetween('orders.created_at', [$from, $to])
                    ->whereNotNull('order_items.product_id');
            })
            ->orderByDesc('available_quantity')
            ->limit($limit)
            ->get(['id', 'name', 'available_quantity'])
            ->map(fn ($p) => [
                'id'       => $p->id,
                'name'     => $p->getTranslations('name'),
                'quantity' => $p->available_quantity,
            ])->all();
    }
}
