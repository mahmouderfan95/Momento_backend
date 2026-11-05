<?php
namespace App\Repositories\Eloquent;
use App\Models\Slider;
use App\Repositories\Contracts\SliderRepositoryInterface;

class SliderRepository extends BaseRepository implements SliderRepositoryInterface
{
    public function __construct(Slider $model)
    {
        parent::__construct($model);
    }
}
