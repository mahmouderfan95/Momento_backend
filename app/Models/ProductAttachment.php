<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductAttachment extends Model
{
    protected $fillable = [
        'product_id',
        'original_path',
        'media_path',
        'file_name',
        'file_extension',
        'is_main',
        'status',
    ];
    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
