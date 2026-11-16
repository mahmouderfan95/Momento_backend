<?php
namespace App\Services\Admin;
use Illuminate\Pagination\LengthAwarePaginator;

class NotificationService
{
    public function list($notifiable, int $perPage = 15): LengthAwarePaginator
    {
        return $notifiable->notifications()->latest()->paginate(min($perPage, 50));
    }

    public function unreadCount($notifiable): int
    {
        return $notifiable->unreadNotifications()->count();
    }

    public function markAsRead($notifiable, string $id): void
    {
        $notifiable->notifications()->whereKey($id)->firstOrFail()->markAsRead();
    }
    public function markAllAsRead($notifiable): void
    {
        $notifiable->unreadNotifications()->update(['read_at' => now()]);
    }
}
