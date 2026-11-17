<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingPrice extends Model
{
    protected $fillable = [
      'shipping_company_id',
      'city_id',
      'price'
    ];
    public function city() : BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }
    public function shippingCompany() : BelongsTo
    {
        return $this->belongsTo(ShippingCompany::class, 'shipping_company_id');
    }
}
