<?php
namespace App\Services\Admin;
use App\Models\Admin;
use App\Repositories\Eloquent\AdminRepository;
use  Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminService
{
    public function __construct(protected AdminRepository $repository){}
    public function list(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $filters);
    }

    public function find(int $id): Admin
    {
        return $this->repository->findOrFail($id);
    }

    public function store(array $data): Admin
    {
        try{
            $admin = DB::transaction(fn () => $this->repository->create($data));
            if ($data['roles']){
                $admin->syncRoles($data['roles']);
            }
            return $admin;
        }catch (\Exception $exception){
            Log::error('error for store category ' . $exception->getMessage());
        }
    }

    public function update(int $id, array $data): Admin
    {
        try{
            $admin = $this->repository->findOrFail($id);

            $updateAdmin =  DB::transaction(fn () => $this->repository->update($admin, $data));

            return $updateAdmin;
        }catch (\Exception $exception){
            Log::error('error for update category ' . $exception->getMessage());
        }
    }

    public function delete(int $id): bool
    {
        $admin = $this->repository->findOrFail($id);

        $deleteAdmin = $this->repository->delete($admin);

        return $deleteAdmin;
    }
}
