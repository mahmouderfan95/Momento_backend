<?php

namespace App\Models;

use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Slider extends Model
{
    use HasTranslations;
    public $translatable = ['title', 'description'];
    protected $fillable = [
      'title',
      'description',
      'image',
      'status',
    ];
    public function scopeActive($query)
    {
        return $query->where('status', StatusEnum::ACTIVE);
    }
    public function getImageUrlAttribute()
    {
        return $this->image == null ? null : asset('storage/sliders/' . $this->image);
    }
}
