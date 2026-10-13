<?php
namespace App\Services\Admin;
use App\Models\Category;
use App\Repositories\Eloquent\CategoryRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Support\Facades\DB;
use  Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class CategoryService
{
    use ApiResponseAble;
    public function __construct(
        protected CategoryRepository $repository,
        private readonly ImageService $imageService,
    ){}
    public function list(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $filters);
    }

    public function find(int $id): Category
    {
        return $this->repository->findOrFail($id);
    }

    public function store(array $data): Category
    {
        try{
            $imagePath = null;
            if(isset($data['image'])){
                $imagePath = $this->imageService->store($data['image'], 'categories');
                $data['image'] = $imagePath;
            }

            return DB::transaction(fn () => $this->repository->create($data));
        }catch (\Exception $exception){
            Log::error('error for store category ' . $exception->getMessage());
        }
    }

    public function update(int $id, array $data): Category
    {
        try{
            $category = $this->repository->findOrFail($id);

            $oldImage = $category->image;

            $newImage = null;

            if (isset($data['image'])) {
                $newImage = $this->imageService->store($data['image'], 'categories');
                $data['image'] = $newImage;
            }

            $updateCategory =  DB::transaction(fn () => $this->repository->update($category, $data));

            if ($newImage) {
                $this->imageService->delete($oldImage,'categories');
            }
            return $updateCategory;
        }catch (\Exception $exception){
            Log::error('error for update category ' . $exception->getMessage());
        }
    }

    public function delete(int $id): bool
    {
        $category = $this->repository->findOrFail($id);

        $imagePath = $category->image;

        $deleteCategory = $this->repository->delete($category);

        if ($deleteCategory)
            $this->imageService->delete($imagePath,'categories');

        return $deleteCategory;
    }
}
