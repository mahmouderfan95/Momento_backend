<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Setting extends Model
{
    use HasTranslations;
    public $translatable = ['site_name', 'site_description'];
    protected $fillable = [
        'site_name',
        'site_description',
        'site_logo',
        'site_address',
        'site_phone',
        'whatsapp',
        'facebook',
        'tiktok',
        'instagram',
    ];
    public function getSiteLogoUrlAttribute()
    {
        return $this->site_logo == null ? null :asset('storage/uploads/settings/' . $this->site_logo);
    }
}
