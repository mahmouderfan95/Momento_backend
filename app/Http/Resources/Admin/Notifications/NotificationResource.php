<?php

namespace App\Http\Resources\Admin\Notifications;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
          'id' => $this->id,
            'data' => $this->data,
            'read' => $this->read_at !== null,
            'created_at' => $this->created_at
        ];
    }
}
