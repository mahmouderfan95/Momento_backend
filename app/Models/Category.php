<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
    public function getImageUrlAttribute()
    {
        return $this->image == null ? null : asset('storage/categories/' . $this->image);
    }
}
