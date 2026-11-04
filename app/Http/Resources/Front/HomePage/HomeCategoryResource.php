<?php

namespace App\Http\Resources\Front\HomePage;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeCategoryResource extends JsonResource
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
            'image' => $this->imageUrl,
            'products_count' => $this->products_count
        ];
    }
}
