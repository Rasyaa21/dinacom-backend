<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChallengeResource extends JsonResource
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
            'challenge_name' => $this->challenge_name,
            'description' => $this->description,
            'required_points' => $this->required_points,
            'reward_points' => $this->reward_points,
        ];
    }
}
