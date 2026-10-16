<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Attribute extends Model
{
    use HasTranslations;
    public $translatable = ['name'];
    protected $fillable = [
        'name',
        'type',
        'status',
    ];
    public function options() : HasMany
    {
        return $this->hasMany(AttributeOption::class, 'attribute_id');
    }
}
