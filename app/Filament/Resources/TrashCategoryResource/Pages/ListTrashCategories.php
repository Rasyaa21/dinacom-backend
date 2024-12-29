<?php

namespace App\Filament\Resources\TrashCategoryResource\Pages;

use App\Filament\Resources\TrashCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTrashCategories extends ListRecords
{
    protected static string $resource = TrashCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
