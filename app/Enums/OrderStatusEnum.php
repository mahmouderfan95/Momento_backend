<?php
namespace App\Enums;
enum OrderStatusEnum : string
{
    case Pending   = 'pending';
    case Completed = 'completed';
    case Shipped   = 'shipped';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';
    case Refunded  = 'refunded';

    public function allowedTransitions(): array
    {
        return match ($this) {
            self::Pending   => [self::Completed, self::Cancelled],
            self::Completed => [self::Shipped, self::Cancelled, self::Refunded],
            self::Shipped   => [self::Delivered],
            self::Delivered => [self::Refunded],
            self::Cancelled, self::Refunded => [],
        };
    }
    public function canTransitionTo(self $to): bool
    {
        return in_array($to, $this->allowedTransitions(), true);
    }

    public function canBeCancelledByCustomer(): bool
    {
        return $this === self::Pending;
    }
}
