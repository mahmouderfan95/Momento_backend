<?php
namespace App\Repositories\Eloquent;
use App\Models\WalletProvider;
use App\Repositories\Contracts\WalletProviderRepositoryInterface;

class WalletProviderRepository extends BaseRepository implements WalletProviderRepositoryInterface
{
    public function __construct(WalletProvider $model)
    {
        parent::__construct($model);
    }
}
