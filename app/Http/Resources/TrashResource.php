<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrashResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'trash_image' => $this->trash_image,
            'trash_name' => $this->trash_name,
            'description' => $this->description,
            'user_id' => $this->user_id,
            'trash_category_id' => $this->trash_category_id,
            'trash_category_name' => $this->getCategoryName($this->trash_category_id),
            'created_at' => $this->created_at->format('Y-m-d')
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
