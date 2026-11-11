<?php
namespace App\Repositories\Eloquent;
use App\Models\WalletNumber;
use App\Repositories\Contracts\WalletNumberRepositoryInterface;

class WalletNumberRepository extends  BaseRepository implements WalletNumberRepositoryInterface
{
    public function __construct(WalletNumber $model)
    {
        parent::__construct($model);
    }
}
