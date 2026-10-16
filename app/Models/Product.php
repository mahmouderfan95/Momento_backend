<?php

namespace App\Models;

use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    use HasTranslations;
    public $translatable = ['name','description'];
    protected $fillable = [
      'name',
      'description',
      'image',
      'price',
      'price_after_discount',
      'available_quantity',
      'status',
      'sku',
      'category_id',
    ];
    public function category() : BelongsTo
    {
        return $this->belongsTo(Category::class,'category_id');
    }
    public function media() :HasMany
    {
        return $this->hasMany(ProductAttachment::class, 'product_id');
    }
    public function scopeActive($query)
    {
        return $query->where('status', StatusEnum::ACTIVE);
    }
    public function getImageUrlAttribute()
    {
        return $this->image == null ? null : asset('storage/uploads/products/' . $this->image) ;
    }
}
