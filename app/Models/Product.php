<?php

namespace App\Models;

use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    use HasTranslations;
    protected static function booted(): void
    {
        static::deleting(function (Product $product) {
            $product->media->each(function ($media) {
                Storage::disk('public')->delete($media->original_path);
                $media->delete();
            });
        });

        static::deleted(function (Product $product) {
            if ($product->image) {
                Storage::disk('public')->delete('products/' . $product->image);
            }
        });
    }
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
    public function attributeOptions(): BelongsToMany
    {
        return $this->belongsToMany(
            AttributeOption::class,
            'product_attribute_options'
        )->withTimestamps();
    }
    public function scopeActive($query)
    {
        return $query->where('status', StatusEnum::ACTIVE);
    }
    public function getImageUrlAttribute()
    {
        return $this->image == null ? null : asset('storage/products/' . $this->image) ;
    }
}
