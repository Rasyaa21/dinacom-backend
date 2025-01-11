<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAchievementResource extends JsonResource
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
            'user_id' => $this->user_id,
            'achievement_id' => $this->achievement_id,
            'status' => $this->status,
            'progress' => $this->progress,
            'claimable' => $this->claimable,
            'achievement' => new AchievementResource($this->whenLoaded('achievement')),
        ];
    }
}
