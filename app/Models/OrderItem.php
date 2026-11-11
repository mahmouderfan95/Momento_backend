<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
      'order_id',
      'product_id',
      'quantity',
      'price',
      'status',
      'product_snapshot'
    ];
    protected $casts = [
        'product_snapshot' => 'array'
    ];
    public function order() : BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
