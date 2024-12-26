<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'profile_image' => $this->profile_image,
            'points' => $this->points,
            'level' => $this->level,
            'rank' => $this->rank,
            'uuid' => $this->uuid
        ];
    }

    public static $wrap = 'user';
}
