<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrashLocationResResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'trash_category_id' => $this->trash_category_id,
            'trash_category_name' => $this->getCategoryName($this->trash_category_id)
        ];
    }

    private function getCategoryName(int $categoryId): ?string
    {
        switch ($categoryId) {
            case 1:
                return 'Organik';
            case 2:
                return 'Anorganik';
            case 3:
                return 'Limbah';
            default:
                return null;
        }
    }
}
