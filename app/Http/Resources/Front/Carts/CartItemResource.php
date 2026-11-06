<?php

namespace App\Http\Resources\Front\Carts;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $currentPrice = $this->product->current_price;

        return [
            'id'            => $this->id,
            'product' => [
                'id'    => $this->product->id,
                'name'  => $this->product->name,
                'image' => $this->product->image_url,
            ],
            'quantity'      => $this->quantity,
            'price'         => (float) $this->price,
            'total'         => (float) $this->total,
            'price_changed' => (float) $this->price !== (float) $currentPrice,
            'current_price' => (float) $currentPrice,
            'options' => $this->attributeOptions->map(fn ($o) => [
                'attribute' => $o->attribute->name,
                'type'      => $o->attribute->type,
                'name'      => $o->name,
                'value'     => $o->value,
            ])->values(),
        ];
    }
}
