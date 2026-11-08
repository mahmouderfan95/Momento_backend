<?php
namespace App\Services\Front;
use App\Models\Product;
use App\Models\Rating;
use App\Models\User;

class RatingService
{
    public function store(User $user, int $productId, array $data): Rating
    {
        Product::query()->Active()->findOrFail($productId);


        return Rating::query()->updateOrCreate(
            ['user_id' => $user->id, 'product_id' => $productId],
            ['rating' => $data['rating'], 'comment' => $data['comment'] ?? null],
        );
    }
}
