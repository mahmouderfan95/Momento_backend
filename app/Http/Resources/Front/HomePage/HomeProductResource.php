<?php

namespace App\Http\Resources\Front\HomePage;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $options = $this->attributeOptions;
        return [
          'id' => $this->id,
            'name' => [
                'ar' => $this->getTranslation('name','ar'),
                'en' => $this->getTranslation('name','en'),
            ],
            'image' => $this->imageUrl,
            'price'  => $this->price,
            'price_after_discount' => $this->has_active_offer ? (float) $this->price_after_discount : null,
            'discount_percentage'  => $this->discount_percentage,
            'has_offer'            => $this->has_active_offer,
            'colors' => $options
                ->filter(fn ($o) => $o->attribute->type === 'color')
                ->map(fn ($o) => [
                    'id'   => $o->id,
                    'name' => $o->name,
                    'hex'  => $o->value,
                ])->values(),

            'sizes' => $options
                ->filter(fn ($o) => $o->attribute->type === 'select')
                ->map(fn ($o) => [
                    'id'   => $o->id,
                    'name' => $o->name,
                ])->values(),
        ];
    }
}
