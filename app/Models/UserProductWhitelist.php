<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProductWhitelist extends Model
{
    protected $fillable = [
      'user_id',
      'product_id',
      'guest_token'
    ];
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function scopeForOwner($query, ?int $userId, ?string $guestToken)
    {
        return $userId
            ? $query->where('user_id', $userId)
            : $query->whereNull('user_id')->where('guest_token', $guestToken);
    }
}
