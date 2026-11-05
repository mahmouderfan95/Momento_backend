<?php

namespace App\Http\Resources\Admin\Coupons;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'toke' => $this->token,
            'value' => $this->value,
            'min_order_total' => $this->min_order_total,
            'usage_limit' => $this->usage_limit,
            'used_count' => $this->used_count,
            'status' => $this->status,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date
        ];
    }
}
