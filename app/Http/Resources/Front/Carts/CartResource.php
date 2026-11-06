<?php

namespace App\Http\Resources\Front\Carts;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'items'       => CartItemResource::collection($this->items),
            'items_count' => $this->items->sum('quantity'),
            'total'       => (float) $this->total,
        ];
    }
}
