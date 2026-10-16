<?php
namespace App\Repositories\Eloquent;
use App\Models\Attribute;
use App\Repositories\Contracts\AttributeRepositoryInterface;

class AttributeRepository extends BaseRepository implements AttributeRepositoryInterface
{
    public function __construct(Attribute $model)
    {
        parent::__construct($model);
    }
}
