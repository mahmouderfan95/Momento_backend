<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'site_name' => [
                'en' => $this->getTranslation('site_name','en'),
                'ar' => $this->getTranslation('site_name','ar'),
            ],
            'site_description' => [
                'en' => $this->getTranslation('site_description','en'),
                'ar' => $this->getTranslation('site_description','ar'),
            ],
            'site_address'     => $this->site_address,
            'site_logo'        => $this->siteLogoUrl,
            'site_phone'       => $this->site_phone,
            'whatsapp'         => $this->whatsapp,
            'facebook'         => $this->facebook,
            'tiktok'           => $this->tiktok,
            'instagram'        => $this->instagram,
        ];
    }
}
