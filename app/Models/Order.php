<?php

namespace App\Models;

use App\Enums\OrderStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
      'order_number',
      'user_id',
      'coupon_id',
      'created_by',
      'status',
      'sub_total',
      'total',
      'payment_type',
      'notes',
      'discount',
      'cancelled_at',
      'cancel_reason',
      'conversion_image',
      'address',
      'customer_name',
      'customer_phone',
    ];
    protected $casts = [
      'status' => OrderStatusEnum::class,
    ];
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function coupon() : BelongsTo
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }
    public function createdBy() : BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
    public function orderItems() : HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
    public function getConversionImageUrlAttribute()
    {
        return $this->conversion_image == null ? null : asset('storage/uploads/orders/' . $this->conversion_image) ;
    }
}
