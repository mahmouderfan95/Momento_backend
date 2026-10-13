<?php
namespace App\Services\Admin;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use App\Repositories\Eloquent\RoleRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoleService
{
    public function __construct(protected RoleRepository $repository){}
    public function list(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $filters);
    }

    public function find(int $id): Role
    {
        return $this->repository->findOrFail($id);
    }

    public function store(array $data)
    {
        try{
            $data['guard_name'] = 'admin';
            $role = DB::transaction(fn () => $this->repository->create($data));
            $role->syncPermissions($data['permissions']);
            return $role->load('permissions');
        }catch (\Exception $exception){
            Log::error('error for store role ' . $exception->getMessage());
        }
    }

    public function update(int $id, array $data): Role
    {
        try{
            $role = $this->repository->findOrFail($id);

            $role = DB::transaction(fn () => $this->repository->update($role, $data));
            $role->syncPermissions($data['permissions']);
            return $role->load('permissions');
        }catch (\Exception $exception){
            Log::error('error for update category ' . $exception->getMessage());
        }
    }

    public function delete(int $id): bool
    {
        $category = $this->repository->findOrFail($id);

        return $this->repository->delete($category);
    }
}
