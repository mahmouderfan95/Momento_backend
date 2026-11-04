<?php

namespace App\Http\Resources\Front\Products;

use App\Http\Resources\Front\Attributes\AttributeOptionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductAttributeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $attribute = $this->resource['attribute'];
        return [
            'id'      => $attribute->id,
            'name'    => $attribute->getTranslations('name'),
            'type'    => $attribute->type, // select | color
            'options' => AttributeOptionResource::collection($this->resource['options']),
        ];
    }
}
