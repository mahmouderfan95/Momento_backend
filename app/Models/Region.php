<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class Region extends Model
{
    use HasTranslations;
    public $translatable = ['name'];
    protected $fillable = [
      'name',
      'city_id',
      'status'
    ];
    public function city() : BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }
}
