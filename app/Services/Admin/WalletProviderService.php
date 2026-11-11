<?php
namespace App\Services\Admin;
use App\Models\Coupon;
use App\Models\WalletProvider;
use App\Repositories\Eloquent\WalletProviderRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WalletProviderService
{
    public function __construct(protected WalletProviderRepository $repository){}
    public function list(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $filters);
    }

    public function find(int $id): WalletProvider
    {
        return $this->repository->findOrFail($id);
    }

    public function store(array $data): WalletProvider
    {
        /** @var WalletProvider $walletProvider */
        $walletProvider = DB::transaction(fn () => $this->repository->create($data));

        return $walletProvider;
    }

    public function update(int $id, array $data): WalletProvider
    {
        try{
            $provider = $this->repository->findOrFail($id);

            $updateProvider=  DB::transaction(fn () => $this->repository->update($provider, $data));

            return $updateProvider;
        }catch (\Exception $exception){
            Log::error('error for update provider ' . $exception->getMessage());
        }
    }

    public function delete(int $id): bool
    {
        $provider = $this->repository->findOrFail($id);

        $deleteProvider = $this->repository->delete($provider);


        return $deleteProvider;
    }
}
