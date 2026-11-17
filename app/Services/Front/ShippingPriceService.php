<?php
namespace App\Services\Front;
use App\Models\ShippingCompany;
use App\Models\ShippingPrice;

class ShippingPriceService
{
    public function getShippingCost($cityId)
    {
        $defaultShipping = $this->getDefaultShippingCompany();
        return ShippingPrice::query()
            ->select(['id','price'])
            ->where('shipping_company_id',$defaultShipping->id)
            ->where('city_id',$cityId)
            ->first();
    }
    private function getDefaultShippingCompany()
    {
        return ShippingCompany::query()
            ->where('is_default', '=', 1)
            ->first();
    }
}
