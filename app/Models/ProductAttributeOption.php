<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductAttributeOption extends Model
{
    protected $fillable = [
        'product_id',
        'attribute_option_id'
    ];
    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function attributeOption() : BelongsTo
    {
        return $this->belongsTo(AttributeOption::class, 'attribute_option_id');
    }
}
