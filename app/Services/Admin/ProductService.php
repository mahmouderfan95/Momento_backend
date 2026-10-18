<?php
namespace App\Services\Admin;
use App\Models\Product;
use App\Repositories\Eloquent\ProductRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductService
{
    public function __construct(
        protected ProductRepository $repository,
        private readonly ImageService $imageService,
    ){}
    public function list(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $filters,['media','attributeOptions.attribute']);
    }

    public function find(int $id): Product
    {
        return $this->repository->findOrFail($id);
    }

    public function store(array $data): Product
    {
        $optionIds   = $data['attribute_option_ids'] ?? [];
        $attachments = $data['attachments'] ?? [];
        unset($data['attribute_option_ids'], $data['attachments']);

        $uploadedImage = null;
        $uploadedMedia = [];

        try {
            if (isset($data['image'])) {
                $uploadedImage = $this->imageService->store($data['image'], 'products');
                $data['image'] = $uploadedImage;
            }

            if (!empty($attachments)) {
                $files = is_array($attachments) ? $attachments : [$attachments];
                $uploadedMedia = $this->imageService->storeMany($files, 'products/media');
            }


            $product = DB::transaction(function () use ($data, $optionIds, $uploadedMedia) {
                /** @var Product $product */
                $product = $this->repository->create($data);

                if (!empty($optionIds)) {
                    $product->attributeOptions()->attach($optionIds);
                }

                if (!empty($uploadedMedia)) {
                    $product->media()->createMany($uploadedMedia);
                }

                return $product;
            });

            return $product->load(['media', 'attributeOptions.attribute']);

        } catch (\Throwable $e) {
            if ($uploadedImage) {
                $this->imageService->delete($uploadedImage, 'products');
            }

            foreach ($uploadedMedia as $media) {
                $this->imageService->delete($media['original_path'], 'products/media');
            }

            Log::error('error for store product: ' . $e->getMessage());

            throw $e; // مهم: سيب الخطأ يطلع للـ Global Handler
        }
    }

    public function update(int $id, array $data): Product
    {
        /** @var Product $product */
        $product = $this->repository->findOrFail($id);

        $oldImage = $product->image;
        $newImage = null;
        $uploadedMedia = [];
        $optionIds   = $data['attribute_option_ids'] ?? [];

        $attachments = $data['attachments'] ?? [];
        unset($data['attachments'],$data['attribute_option_ids']);

        if (isset($data['image'])) {
            $newImage = $this->imageService->store($data['image'], 'products');
            $data['image'] = $newImage;
        }

        try {
            $updated = DB::transaction(function () use ($product, $data, $attachments, &$uploadedMedia) {
                /** @var Product $updated */
                $updated = $this->repository->update($product, $data);

                if (!empty($attachments)) {
                    $files = is_array($attachments) ? $attachments : [$attachments];

                    $uploadedMedia = $this->imageService->storeMany($files, 'products/media');

                    if (!empty($uploadedMedia)) {
                        $updated->media()->createMany($uploadedMedia);
                    }
                }
                if (!empty($optionIds)) {
                    $product->attributeOptions()->sync($optionIds);
                }

                return $updated;
            });
        } catch (\Throwable $e) {
            if ($newImage) {
                $this->imageService->delete($newImage, 'products');
            }

            Log::error('error for update product: ' . $e->getMessage());

            throw $e;
        }

        if ($newImage && $oldImage) {
            $this->imageService->delete($oldImage, 'products');
        }

        return $updated->load('media');
    }

    public function delete(int $id): bool
    {
        $product = $this->repository->findOrFail($id);

        return $this->repository->delete($product);
    }
}
