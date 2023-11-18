<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\NotificationType;
use App\Classes\Common;

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
            'label' => NotificationType::getDescription($this->type),
            'type' => $this->type,
            'content' => $this->content,
            'status' => $this->status,
            'created_at' => Common::formatDateBaseOnSetting($this->created_at, user()->isSuperUser()),
            'parent_table' => $this->parent_table,
            'parent_id' => $this->parent_id,
        ];
    }
}
