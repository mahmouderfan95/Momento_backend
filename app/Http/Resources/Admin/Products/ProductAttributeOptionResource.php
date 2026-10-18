<?php

namespace App\Http\Resources\Admin\Products;

use App\Http\Resources\Admin\AttributreOption\AttributeOptionResource;
use App\Http\Resources\Admin\Attributres\AttributeResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductAttributeOptionResource extends JsonResource
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
            'value' => $this->value,
            'status' => $this->status,
            'attribute' => AttributeResource::make($this->whenLoaded('attribute')),
        ];
    }
}
