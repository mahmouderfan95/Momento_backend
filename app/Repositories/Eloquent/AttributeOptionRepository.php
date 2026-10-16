<?php
namespace App\Repositories\Eloquent;
use App\Models\AttributeOption;
use App\Repositories\Contracts\AttributeOptionRepositoryInterface;

class AttributeOptionRepository extends BaseRepository implements AttributeOptionRepositoryInterface
{
    public function __construct(AttributeOption $model)
    {
        parent::__construct($model);
    }
}
