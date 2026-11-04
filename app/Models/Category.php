<?php

namespace App\Models;

use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasTranslations;
    public $translatable = ['name'];
    protected $fillable = [
        'name',
        'image',
        'status',
    ];
    public function products() : HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
    }
    public function scopeActive($query)
    {
        return $query->where('status', StatusEnum::ACTIVE->value);
    }
    public function getImageUrlAttribute()
    {
        return $this->image == null ? null : asset('storage/categories/' . $this->image);
    }
}
