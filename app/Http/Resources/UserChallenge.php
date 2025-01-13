<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserChallenge extends JsonResource
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
            'challenge_id' => $this->challenge_id,
            'progress' => $this->progress,
            'status' => $this->status,
            'claimable' => $this->claimable,
            'claimed_at' => $this->claimed_at,
            'challenge' => new ChallengeResource($this->whenLoaded('challenge')),
        ];
    }
}
