<?php

namespace App\Http\Resources\Admin\ShippingPrice;

use App\Http\Resources\Admin\Cities\CityResource;
use App\Http\Resources\Admin\ShippingCompany\ShippingCompanyResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShippingPriceResource extends JsonResource
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
            'shipping_company' => ShippingCompanyResource::make($this->whenLoaded('shippingCompany')),
            'city' => CityResource::make($this->whenLoaded('city')),
//          'region' => RegionResource::make($this->whenLoaded('region')),
            'price' => $this->price
        ];
    }
}
