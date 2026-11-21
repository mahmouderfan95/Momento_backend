<?php
namespace App\Services\Admin;
use App\Models\Setting;
use App\Repositories\Eloquent\SettingRepository;
use Illuminate\Support\Facades\DB;

class SettingService
{
    public function __construct(
        protected SettingRepository $repository,
        private readonly ImageService $imageService
    ){}
    public function get(): Setting
    {
        return $this->repository->get() ?? new Setting();
    }
    public function update(array $data): Setting
    {
        $setting = $this->repository->get();

        $oldImage = $setting->site_logo ?? null;

        $newImage = null;

        if (isset($data['site_logo'])) {
            $newImage = $this->imageService->store($data['site_logo'], 'site');
            $data['site_logo'] = $newImage;
        }
        $updateSetting =  DB::transaction(fn () => $this->repository->save($data));

        if ($newImage) {
            $this->imageService->delete($oldImage,'site');
        }
        return $updateSetting;
    }
}
