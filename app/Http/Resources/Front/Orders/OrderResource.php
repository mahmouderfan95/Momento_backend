<?php

namespace App\Http\Resources\Front\Orders;

use App\Http\Resources\Admin\Coupons\CouponResource;
use App\Http\Resources\Front\Auth\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'order_number' => $this->order_number,
            'total' => $this->total,
            'sub_total' => $this->sub_total,
            'address' => $this->address,
            'payment_type' => $this->payment_type,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'items_count'  => (int) $this->items_count,
            'createdBy' => UserResource::make($this->whenLoaded('createdBy')),
            'coupon' => CouponResource::make($this->whenLoaded('coupon')),
            'items' => OrderItemResource::collection($this->whenLoaded('orderItems')),
            'discount' => $this->discount,
//            'can_cancel'   => $this->status->canBeCancelledByCustomer(),
            'cancelled_at' => $this->cancelled_at,
            'cancel_reason' => $this->cancel_reason,
            'customer_name' => $this->customer_name ?? null,
            'customer_phone' => $this->customer_phone ?? null,
        ];
    }
}
