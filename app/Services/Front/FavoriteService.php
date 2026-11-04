<?php
namespace App\Services\Front;
use App\Models\Product;
use App\Repositories\Eloquent\Front\FavoriteRepository;
use App\Support\FavoriteOwner;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class FavoriteService
{
    public function __construct(protected FavoriteRepository $repository){}
    public function toggle(FavoriteOwner $owner, int $productId): array
    {
        Product::query()->Active()->findOrFail($productId);

        $isFavorite = DB::transaction(function () use ($owner, $productId) {
            if ($this->repository->exists($owner, $productId)) {
                $this->repository->remove($owner, $productId);

                return false;
            }

            $this->repository->add($owner, $productId);

            return true;
        });

        return [
            'is_favorite' => $isFavorite,
            'count'       => $this->repository->count($owner),
        ];
    }
    public function list(FavoriteOwner $owner, int $perPage = 12): LengthAwarePaginator
    {
        return $this->repository->paginateProducts($owner, $perPage);
    }
    public function count(FavoriteOwner $owner): int
    {
        return $this->repository->count($owner);
    }
}
