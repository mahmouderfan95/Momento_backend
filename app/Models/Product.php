<?php

namespace App\Models;

use App\Enums\StatusEnum;
use App\Support\FavoriteOwner;
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
      'discount_ends_at',
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
    public function favorites(): HasMany
    {
        return $this->hasMany(UserProductWhitelist::class, 'product_id');
    }
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class, 'product_id');
    }
    public function scopeWithIsFavorite($query, FavoriteOwner $owner)
    {
        if (! $owner->isIdentified()) {
            return $query;
        }

        return $query->withExists(['favorites as is_favorite' => function ($q) use ($owner) {
            $q->forOwner($owner->userId, $owner->guestToken);
        }]);
    }
    public function scopeActive($query)
    {
        return $query->where('status', StatusEnum::ACTIVE->value);
    }
    public function scopeOnOffer($query)
    {
        return $query
            ->whereNotNull('price_after_discount')
            ->whereColumn('price_after_discount', '<', 'price')
            ->where(function ($q) {
                $q->whereNull('discount_ends_at')
                ->orWhere('discount_ends_at', '>', now());
            });
    }
    public function getHasActiveOfferAttribute(): bool
    {
        return $this->price_after_discount !== null
            && $this->price_after_discount < $this->price
            && ($this->discount_ends_at === null || $this->discount_ends_at->isFuture());
    }
    public function getDiscountPercentageAttribute(): ?int
    {
        if (! $this->has_active_offer || $this->price <= 0) {
            return null;
        }
        return (int) round((($this->price - $this->price_after_discount) / $this->price) * 100);
    }
    public function getImageUrlAttribute()
    {
        return $this->image == null ? null : asset('storage/products/' . $this->image) ;
    }
    public function getCurrentPriceAttribute(): float
    {
        return (float) ($this->has_active_offer ? $this->price_after_discount : $this->price);
    }
}
