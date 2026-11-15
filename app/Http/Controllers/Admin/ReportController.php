<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Reports\ReportFilterRequest;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\Admin\DashboardReportService;
use App\Services\Admin\SalesReportService;
use App\Services\Admin\ProductReportService;

class ReportController extends Controller
{
    use ApiResponseAble;
    public function __construct(
        protected DashboardReportService $dashboard,
        protected SalesReportService $sales,
        protected ProductReportService $products,
    ){}
    public function dashboard(ReportFilterRequest $r): JsonResponse
    {
        $data = $this->dashboard->summary($r->from(), $r->to());
        return $this->ApiSuccessResponse($data);
    }
    public function salesOverTime(ReportFilterRequest $r): JsonResponse
    {
        $data = $this->sales->overTime($r->from(), $r->to(), $r->groupBy());
        return $this->ApiSuccessResponse($data);
    }
    public function topProducts(ReportFilterRequest $r): JsonResponse
    {
        $data = $this->products->topSelling($r->from(), $r->to(), $r->limit());
        return $this->ApiSuccessResponse($data);
    }

    public function stock(): JsonResponse
    {
        $data = $this->products->stock();
        return $this->ApiSuccessResponse($data);
    }

    public function deadStock(ReportFilterRequest $r): JsonResponse
    {
        $data = $this->products->deadStock($r->from(), $r->to(), $r->limit());
        return $this->ApiSuccessResponse($data);
    }
}
