<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
//    public $afterCommit = true;
    public function __construct(protected Order $order)
    {
        //
    }

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
            'type'         => 'new_order',
            'order_id'     => $this->order->id,
            'order_number' => $this->order->order_number,
            'total'        => (float) $this->order->total,
            'customer'     => $this->order->customer_name,
            'title'        => ['ar' => 'طلب جديد', 'en' => 'New order'],
            'message'      => [
                'ar' => "طلب جديد رقم {$this->order->order_number} بقيمة {$this->order->total}",
                'en' => "New order {$this->order->order_number} worth {$this->order->total}",
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
