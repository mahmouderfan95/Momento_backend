<?php
namespace App\Repositories\Contracts\Front;
use App\Support\FavoriteOwner;
use Illuminate\Pagination\LengthAwarePaginator;

interface FavoriteRepositoryInterface
{
    public function exists(FavoriteOwner $owner, int $productId): bool;
    public function add(FavoriteOwner $owner, int $productId): void;
    public function remove(FavoriteOwner $owner, int $productId): int;
    public function paginateProducts(FavoriteOwner $owner, int $perPage = 12): LengthAwarePaginator;
    public function count(FavoriteOwner $owner): int;
}
