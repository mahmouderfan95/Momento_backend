<?php
namespace App\Services\Front;
use Illuminate\Pagination\LengthAwarePaginator;

class NotificationService
{
    public function list($notifiable, bool $onlyUnread = false, int $perPage = 15): LengthAwarePaginator
    {
        $query = $onlyUnread
            ? $notifiable->unreadNotifications()
            : $notifiable->notifications();

        return $query->latest()->paginate(min($perPage, 50));
    }
    public function markAsRead($notifiable, string $id): void
    {
        $notifiable->notifications()->whereKey($id)->firstOrFail()->markAsRead();
    }
}
