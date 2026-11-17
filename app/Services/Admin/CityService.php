<?php
namespace App\Services\Admin;
use App\Models\City;
use App\Repositories\Eloquent\CityRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CityService
{
    public function __construct(protected CityRepository $repository){}
    public function list(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $filters);
    }

    public function find(int $id): City
    {
        return $this->repository->findOrFail($id);
    }

    public function store(array $data): City
    {
        /** @var City $city */
        $city = DB::transaction(fn () => $this->repository->create($data));

        return $city;
    }

    public function update(int $id, array $data): City
    {
        try{
            $category = $this->repository->findOrFail($id);

            $updateCategory =  DB::transaction(fn () => $this->repository->update($category, $data));

            return $updateCategory;
        }catch (\Exception $exception){
            Log::error('error for update city ' . $exception->getMessage());
        }
    }

    public function delete(int $id): bool
    {
        $category = $this->repository->findOrFail($id);

        $deleteCategory = $this->repository->delete($category);


        return $deleteCategory;
    }
}
