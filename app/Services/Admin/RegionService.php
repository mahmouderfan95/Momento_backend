<?php
namespace App\Services\Admin;
use App\Models\Region;
use App\Repositories\Eloquent\RegionRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegionService
{
    public function __construct(protected RegionRepository $repository){}
    public function list(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $filters,['city']);
    }

    public function find(int $id): Region
    {
        return $this->repository->findOrFail($id);
    }

    public function store(array $data): Region
    {
        /** @var Region $region */
        $region = DB::transaction(fn () => $this->repository->create($data));

        return $region;
    }

    public function update(int $id, array $data): Region
    {
        try{
            $category = $this->repository->findOrFail($id);

            $updateCategory =  DB::transaction(fn () => $this->repository->update($category, $data));

            return $updateCategory;
        }catch (\Exception $exception){
            Log::error('error for update region ' . $exception->getMessage());
        }
    }

    public function delete(int $id): bool
    {
        $category = $this->repository->findOrFail($id);

        $deleteCategory = $this->repository->delete($category);


        return $deleteCategory;
    }
}
