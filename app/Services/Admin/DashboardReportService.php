<?php
namespace App\Services\Admin;
use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Carbon;

class DashboardReportService
{
    public function summary(Carbon $from, Carbon $to): array
    {
        $days     = $from->diffInDays($to) + 1;
        $prevFrom = $from->copy()->subDays($days);
        $prevTo   = $from->copy()->subSecond();

        $current  = $this->metrics($from, $to);
        $previous = $this->metrics($prevFrom, $prevTo);

        return [
            'period' => ['from' => $from->toDateString(), 'to' => $to->toDateString()],
            'metrics' => [
                'revenue'         => $this->withChange($current['revenue'], $previous['revenue']),
                'orders_count'    => $this->withChange($current['orders'], $previous['orders']),
                'avg_order_value' => $this->withChange($current['aov'], $previous['aov']),
                'new_customers'   => $this->withChange($current['customers'], $previous['customers']),
            ],
            'pending_orders'   => Order::query()->where('status', OrderStatusEnum::Pending)->count(),
            'low_stock_count'  => Product::query()->active()->where('available_quantity', '<=', 5)->count(),
        ];
    }
    private function metrics(Carbon $from, Carbon $to): array
    {
        $row = Order::query()->revenue()->between($from, $to)
            ->selectRaw('COALESCE(SUM(total),0) as revenue, COUNT(*) as orders')
            ->first();

        $orders = (int) $row->orders;

        return [
            'revenue'   => (float) $row->revenue,
            'orders'    => $orders,
            'aov'       => $orders > 0 ? round($row->revenue / $orders, 2) : 0.0,
            'customers' => User::query()->whereBetween('created_at', [$from, $to])->count(),
        ];
    }
    private function withChange(float|int $current, float|int $previous): array
    {
        return [
            'value'          => $current,
            'previous'       => $previous,
            'change_percent' => $previous > 0
                ? round((($current - $previous) / $previous) * 100, 1)
                : null,
        ];
    }
}
