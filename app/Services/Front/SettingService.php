<?php
namespace App\Services\Front;
use App\Models\Setting;

class SettingService
{
    public function getSetting() : Setting
    {
        return Setting::query()
            ->select(['id','site_name','site_description','site_logo',
                'site_phone','site_address',
                'facebook','instagram','whatsapp','tiktok'])
            ->latest()
            ->first();
    }
}
