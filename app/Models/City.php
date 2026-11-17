<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class City extends Model
{
    use HasTranslations;
    public $translatable = ['name'];
    protected $fillable = [
        'name',
        'status'
    ];
    public function regions() : HasMany
    {
        return $this->hasMany(Region::class, 'city_id');
    }
}
