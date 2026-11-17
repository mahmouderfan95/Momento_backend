<?php
namespace App\Repositories\Eloquent;
use App\Models\Region;
use App\Repositories\Contracts\RegionRepositoryInterface;

class RegionRepository extends  BaseRepository implements RegionRepositoryInterface
{
    public function __construct(Region $model)
    {
        parent::__construct($model);
    }
}
