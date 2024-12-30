<?php

namespace App\Filament\Resources\TrashLocationResource\Pages;

use App\Filament\Resources\TrashLocationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTrashLocations extends ListRecords
{
    protected static string $resource = TrashLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
