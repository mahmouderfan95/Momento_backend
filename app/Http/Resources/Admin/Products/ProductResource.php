<?php

namespace App\Http\Resources\Admin\Products;

use App\Http\Resources\Admin\Categories\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'name' => [
                'ar' => $this->getTranslation('name','ar'),
                'en' => $this->getTranslation('name','en'),
            ],
            'description' => [
                'ar' => $this->getTranslation('description','ar'),
                'en' => $this->getTranslation('description','en'),
            ],
            'status' => $this->status,
            'image' => $this->imageUrl,
            'price' => $this->price,
            'price_after_discount' => $this->price_after_discount,
            'available_quantity' => $this->available_quantity,
            'category' => CategoryResource::make($this->whenLoaded('category')),
            'attributeOptions' => ProductAttributeOptionResource::collection($this->whenLoaded('attributeOptions')),
            'media' => ProductMediaResource::collection($this->whenLoaded('media')),
        ];
    }
}
