<?php
namespace App\Repositories\Eloquent;
use App\Models\ShippingPrice;
use App\Repositories\Contracts\ShippingPriceRepositoryInterface;

class ShippingPriceRepository extends BaseRepository implements ShippingPriceRepositoryInterface
{
    public function __construct(ShippingPrice $model)
    {
        parent::__construct($model);
    }
}
