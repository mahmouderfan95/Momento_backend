<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Notifications\NotificationCollection;
use App\Services\Front\NotificationService;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use ApiResponseAble;
    public function __construct(protected NotificationService $service){}
    public function index(Request $request): JsonResponse
    {
        $notifications = $this->service->list(
            $request->user(),
            $request->boolean('unread'),
            (int) $request->get('per_page', 15)
        );

        return $this->ApiSuccessResponse(NotificationCollection::make($notifications));
    }
    public function markAsRead(Request $request, string $id): JsonResponse
    {
        $this->service->markAsRead($request->user(), $id);

        return $this->ApiSuccessResponse(null, 'تم تعليم الإشعار كمقروء');
    }
}
