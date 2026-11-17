<?php
namespace App\Services\Admin;
use App\Models\ShippingPrice;
use App\Repositories\Eloquent\ShippingPriceRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ShippingPriceService
{
    public function __construct(protected ShippingPriceRepository $repository){}
    public function list(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $filters,['shippingCompany','city']);
    }

    public function find(int $id): ShippingPrice
    {
        return $this->repository->findOrFail($id);
    }

    public function store(array $data): ShippingPrice
    {
        /** @var ShippingPrice $shippingPrice */
        $shippingPrice = DB::transaction(fn () => $this->repository->create($data));

        return $shippingPrice->load('shippingCompany','city');
    }

    public function update(int $id, array $data): ShippingPrice
    {
        try{
            $category = $this->repository->findOrFail($id);

            $updateCategory =  DB::transaction(fn () => $this->repository->update($category, $data));

            return $updateCategory;
        }catch (\Exception $exception){
            Log::error('error for update shipping company ' . $exception->getMessage());
        }
    }

    public function delete(int $id): bool
    {
        $category = $this->repository->findOrFail($id);

        $deleteCategory = $this->repository->delete($category);


        return $deleteCategory;
    }
}
