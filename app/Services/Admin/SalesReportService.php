<?php
namespace App\Services\Admin;
use App\Models\Order;
use Illuminate\Support\Carbon;

class SalesReportService
{
    public function overTime(Carbon $from, Carbon $to, string $groupBy): array
    {
        $format = match ($groupBy) {
            'month' => '%Y-%m',
            'week'  => '%x-W%v',
            default => '%Y-%m-%d',
        };

        return Order::query()->revenue()->between($from, $to)
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as period")
            ->selectRaw('COUNT(*) as orders_count, SUM(total) as revenue, SUM(discount) as discounts')
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->map(fn ($r) => [
                'period'       => $r->period,
                'orders_count' => (int) $r->orders_count,
                'revenue'      => (float) $r->revenue,
                'discounts'    => (float) $r->discounts,
            ])->all();
    }
}
