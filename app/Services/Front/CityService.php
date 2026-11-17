<?php
namespace App\Services\Front;
use App\Enums\StatusEnum;
use App\Models\City;
use App\Models\Region;
use Illuminate\Support\Collection;

class CityService
{
    public function getCities() : Collection
    {
        return City::query()->where('status',StatusEnum::ACTIVE->value)->get();
    }
    public function getRegionByCityId(int $cityId) : Collection
    {
        return Region::query()->where('city_id',$cityId)
            ->where('status',StatusEnum::ACTIVE->value)
            ->get();
    }
}
