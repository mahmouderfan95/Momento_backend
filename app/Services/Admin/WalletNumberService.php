<?php
namespace App\Services\Admin;
use App\Models\WalletNumber;
use App\Models\WalletProvider;
use App\Repositories\Eloquent\WalletNumberRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WalletNumberService
{
    public function __construct(protected WalletNumberRepository $repository){}
    public function list(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $filters);
    }

    public function find(int $id): WalletNumber
    {
        return $this->repository->findOrFail($id);
    }

    public function store(array $data): WalletNumber
    {
        /** @var WalletNumber $walletNumber */
        $walletNumber = DB::transaction(fn () => $this->repository->create($data));

        return $walletNumber;
    }

    public function update(int $id, array $data): WalletNumber
    {
        try{
            $number = $this->repository->findOrFail($id);

            $updateNumber=  DB::transaction(fn () => $this->repository->update($number, $data));

            return $updateNumber;
        }catch (\Exception $exception){
            Log::error('error for update number ' . $exception->getMessage());
        }
    }

    public function delete(int $id): bool
    {
        $number = $this->repository->findOrFail($id);

        $deleteNumber = $this->repository->delete($number);


        return $deleteNumber;
    }
}
