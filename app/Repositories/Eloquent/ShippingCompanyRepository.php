<?php
namespace App\Repositories\Eloquent;
use App\Models\ShippingCompany;
use App\Repositories\Contracts\BaseRepositoryInterface;

class ShippingCompanyRepository extends BaseRepository implements BaseRepositoryInterface
{
    public function __construct(ShippingCompany $model)
    {
        parent::__construct($model);
    }
}
