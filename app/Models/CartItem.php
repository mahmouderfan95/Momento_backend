<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CartItem extends Model
{
    protected $fillable = [
      'cart_id',
      'product_id',
      'quantity',
      'price',
      'total',
      'options_hash'
    ];
    public function cart() : BelongsTo
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }
    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function attributeOptions(): BelongsToMany
    {
        return $this->belongsToMany(AttributeOption::class, 'cart_item_attribute_options')
            ->withTimestamps();
    }
}
