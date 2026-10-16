<?php
namespace App\Services\Admin;
use App\Models\Admin;
use App\Models\Attribute;
use App\Repositories\Eloquent\AttributeRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AttributeService
{
    public function __construct(protected AttributeRepository $repository){}
    public function list(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $filters);
    }

    public function find(int $id): Attribute
    {
        return $this->repository->findOrFail($id);
    }

    public function store(array $data): Attribute
    {
        try{
            return DB::transaction(fn () => $this->repository->create($data));
        }catch (\Exception $exception){
            Log::error('error for store attribute ' . $exception->getMessage());
        }
    }

    public function update(int $id, array $data): Attribute
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
