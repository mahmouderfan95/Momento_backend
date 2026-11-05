<?php
namespace App\Services\Admin;
use App\Models\Coupon;
use App\Repositories\Eloquent\CouponRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CouponService
{
    public function __construct(protected CouponRepository $repository){}
    public function list(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $filters);
    }

    public function find(int $id): Coupon
    {
        return $this->repository->findOrFail($id);
    }

    public function store(array $data): Coupon
    {
        $data['code'] = $data['code'] ?? $this->generateUniqueCode('MOM');
        $data['token'] = $data['token'] ?? (string) Str::uuid();
        $data['created_by'] = auth()->id();

        /** @var Coupon $coupon */
        $coupon = DB::transaction(fn () => $this->repository->create($data));

        return $coupon;
    }

    public function update(int $id, array $data): Coupon
    {
        try{
            $category = $this->repository->findOrFail($id);

            $updateCategory =  DB::transaction(fn () => $this->repository->update($category, $data));

            return $updateCategory;
        }catch (\Exception $exception){
            Log::error('error for update coupon ' . $exception->getMessage());
        }
    }

    public function delete(int $id): bool
    {
        $category = $this->repository->findOrFail($id);

        $deleteCategory = $this->repository->delete($category);


        return $deleteCategory;
    }

    private function generateUniqueCode(string $prefix = 'MOM', int $length = 6): string
    {
        do {
            $code = $prefix . '-' . strtoupper(Str::random($length));
        } while ($this->repository->codeExists($code));

        return $code;
    }
}
