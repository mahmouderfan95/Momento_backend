<?php

namespace App\Http\Resources\Admin\Regions;

use App\Http\Resources\Admin\Cities\CityResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegionResource extends JsonResource
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
            'city' => CityResource::make($this->whenLoaded('city')),
        ];
    }
}
