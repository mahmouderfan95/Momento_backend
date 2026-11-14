<?php
namespace App\Repositories\Eloquent;
use App\Models\Setting;
use App\Repositories\Contracts\SiteRepositoryInterface;

class SettingRepository implements SiteRepositoryInterface
{
    public function get(): ?Setting
    {
        return Setting::query()->first();
    }
    public function save(array $data): Setting
    {
        $setting = Setting::query()->first();

        if ($setting) {
            $setting->update($data);

            return $setting->refresh();
        }

        return Setting::query()->create($data);
    }
}
