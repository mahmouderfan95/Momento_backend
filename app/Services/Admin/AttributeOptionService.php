<?php
namespace App\Services\Admin;
use App\Models\AttributeOption;
use App\Repositories\Eloquent\AttributeOptionRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class AttributeOptionService
{
    public function __construct(protected AttributeOptionRepository $repository){}
    public function list(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $filters);
    }

    public function find(int $id): AttributeOption
    {
        return $this->repository->findOrFail($id);
    }

    public function store(array $data): AttributeOption
    {
        try{
            return DB::transaction(fn () => $this->repository->create($data));
        }catch (\Exception $exception){
            Log::error('error for store attribute ' . $exception->getMessage());
        }
    }

    public function update(int $id, array $data): AttributeOption
    {
        try{
            $attribute = $this->repository->findOrFail($id);

            $updateAttribute =  DB::transaction(fn () => $this->repository->update($attribute, $data));

            return $updateAttribute;
        }catch (\Exception $exception){
            Log::error('error for update attribute ' . $exception->getMessage());
        }
    }

    public function delete(int $id): bool
    {
        $attribute = $this->repository->findOrFail($id);

        $deleteAttribute = $this->repository->delete($attribute);

        return $deleteAttribute;
    }
}
