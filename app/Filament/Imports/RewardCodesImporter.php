<?php

namespace App\Filament\Imports;

use App\Models\RewardCode;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class RewardCodesImporter extends Importer
{
    protected static ?string $model = RewardCode::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('code')
                ->requiredMapping()
                ->rules(['required', 'max:255']),

            ImportColumn::make('reward_id')
                ->requiredMapping()
                ->rules(['required', 'integer', 'exists:rewards,id']),
        ];
    }

    public function resolveRecord(): ?RewardCode
    {
        return RewardCode::create([
            'reward_id' => $this->data['reward_id'],
            'code' => $this->data['code'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your reward codes import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
