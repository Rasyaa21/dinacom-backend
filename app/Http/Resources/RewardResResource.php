<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RewardResResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'reward_name' => $this->reward_name,
            'reward_image' => $this->reward_image,
            'description' => $this->description,
            'points_required' => $this->points_required,
            'stock' => $this->stock,
        ];
    }
}
