<?php
namespace App\Services\Admin;
use App\Models\Slider;
use App\Repositories\Eloquent\SliderRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SliderService
{
    public function __construct(
        protected SliderRepository $repository,
        private readonly ImageService $imageService
    ){}
    public function list(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $filters);
    }

    public function find(int $id): Slider
    {
        return $this->repository->findOrFail($id);
    }

    public function store(array $data): Slider
    {
        try{
            $imagePath = null;
            if(isset($data['image'])){
                $imagePath = $this->imageService->store($data['image'], 'sliders');
                $data['image'] = $imagePath;
            }

            return DB::transaction(fn () => $this->repository->create($data));
        }catch (\Exception $exception){
            Log::error('error for store slider ' . $exception->getMessage());
        }
    }

    public function update(int $id, array $data): Slider
    {
        try{
            $slider = $this->repository->findOrFail($id);

            $oldImage = $slider->image;

            $newImage = null;

            if (isset($data['image'])) {
                $newImage = $this->imageService->store($data['image'], 'sliders');
                $data['image'] = $newImage;
            }

            $updateSlider =  DB::transaction(fn () => $this->repository->update($slider, $data));

            if ($newImage) {
                $this->imageService->delete($oldImage,'sliders');
            }
            return $updateSlider;
        }catch (\Exception $exception){
            Log::error('error for update slider ' . $exception->getMessage());
        }
    }

    public function delete(int $id): bool
    {
        $slider = $this->repository->findOrFail($id);

        $imagePath = $slider->image;

        $deleteSlider = $this->repository->delete($slider);

        if ($deleteSlider)
            $this->imageService->delete($imagePath,'sliders');

        return $deleteSlider;
    }
}
