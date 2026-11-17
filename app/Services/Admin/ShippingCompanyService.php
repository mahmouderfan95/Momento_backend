<?php
namespace App\Services\Admin;
use App\Models\ShippingCompany;
use App\Repositories\Eloquent\ShippingCompanyRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ShippingCompanyService
{
    public function __construct(protected ShippingCompanyRepository $repository){}
    public function list(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $filters);
    }

    public function find(int $id): ShippingCompany
    {
        return $this->repository->findOrFail($id);
    }

    public function store(array $data): ShippingCompany
    {
        /** @var ShippingCompany $shippingCompany */
        $shippingCompany = DB::transaction(fn () => $this->repository->create($data));

        return $shippingCompany;
    }

    public function update(int $id, array $data): ShippingCompany
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
