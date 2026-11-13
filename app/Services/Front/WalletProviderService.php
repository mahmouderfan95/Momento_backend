<?php
namespace App\Services\Front;
use App\Enums\StatusEnum;
use App\Models\WalletNumber;
use App\Models\WalletProvider;
use Illuminate\Support\Collection;

class WalletProviderService
{
    public function getWalletProviders() : Collection
    {
        return WalletProvider::query()
            ->select('id','name','status')
            ->where('status', StatusEnum::ACTIVE->value)
            ->get();
    }
    public function getWalletNumbersByProviderId(int $id) : Collection
    {
        return WalletNumber::query()
            ->select('id','number','wallet_provider_id')
            ->where('wallet_provider_id', $id)
            ->where('status',StatusEnum::ACTIVE->value)
            ->get();
    }
}
