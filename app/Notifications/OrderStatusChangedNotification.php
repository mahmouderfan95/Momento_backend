<?php

namespace App\Notifications;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusChangedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
//    public $afterCommit = true;

    public function __construct(
        protected Order $order,
        protected OrderStatusEnum $newStatus,
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type'         => 'order_status_changed',
            'order_number' => $this->order->order_number,
            'status'       => $this->newStatus->value,
            'title'        => ['ar' => 'تحديث حالة الطلب', 'en' => 'Order status updated'],
            'message'      => [
                'ar' => "طلبك رقم {$this->order->order_number} أصبح: {$this->newStatus->label()}",
                'en' => "Your order {$this->order->order_number} is now: {$this->newStatus->value}",
            ],
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
