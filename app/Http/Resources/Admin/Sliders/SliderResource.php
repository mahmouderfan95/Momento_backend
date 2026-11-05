<?php

namespace App\Http\Resources\Admin\Sliders;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource
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
            'title' => [
                'ar' => $this->getTranslation('title','ar'),
                'en' => $this->getTranslation('title','en'),
            ],
            'description' => [
                'ar' => $this->getTranslation('description','ar'),
                'en' => $this->getTranslation('description','en'),
            ],
            'image' => $this->imageUrl,
            'status' => $this->status
        ];
    }
}
