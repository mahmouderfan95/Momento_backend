<?php

namespace App\Models;

use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'value',
        'min_order_total',
        'usage_limit',
        'used_count',
        'status',
        'start_date',
        'end_date',
        'created_by',
        'token'
    ];
    public function scopeActive($query)
    {
        return $query->where('status', StatusEnum::ACTIVE->value);
    }
    public function createdBy() : BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
}
