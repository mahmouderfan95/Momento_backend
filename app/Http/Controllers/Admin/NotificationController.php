<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Notifications\NotificationCollection;
use App\Http\Resources\Admin\Notifications\NotificationResource;
use App\Services\Admin\NotificationService;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use ApiResponseAble;
    public function __construct(protected NotificationService $service){}
    public function index(Request $request): JsonResponse
    {
        $items = $this->service->list($request->user(), (int) $request->get('per_page', 15));
        return $this->ApiSuccessResponse(NotificationCollection::make($items));
    }
    public function unreadCount(Request $request): JsonResponse
    {
        return $this->ApiSuccessResponse(['count' => $this->service->unreadCount($request->user())]);
    }

    public function markAsRead(Request $request, string $id): JsonResponse
    {
        $this->service->markAsRead($request->user(), $id);

        return $this->ApiSuccessResponse(null, 'تم');
    }
    public function markAllAsRead(Request $request): JsonResponse
    {
        $this->service->markAllAsRead($request->user());

        return $this->ApiSuccessResponse(null, 'تم');
    }
}
