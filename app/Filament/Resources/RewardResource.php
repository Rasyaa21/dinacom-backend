<?php

namespace App\Filament\Resources;

use App\Filament\Imports\RewardCodesImporter;
use App\Filament\Resources\RewardResource\Pages;
use App\Models\Reward;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RewardResource extends Resource
{
    protected static ?string $model = Reward::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('reward_name')
                    ->required(),
                Forms\Components\TextInput::make('points_required')
                    ->required(),
                Forms\Components\FileUpload::make('reward_image')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('stock')
                    ->required()
                    ->default(0)
                    ->readOnly(),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull()
                    ->required(),
                Forms\Components\Repeater::make('codes')
                    ->relationship('codes')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->required()
                    ])
                    ->columnSpanFull()
                    ->required()
                    ->afterStateUpdated(function ($state, callable $set){
                        $set('stock', count($state));
                    })
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reward_name')
                    ->label('Reward Name'),
                Tables\Columns\TextColumn::make('points_required')
                    ->label('Points Required'),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Stock'),
            ])
            ->filters([
                //
            ])
            ->headerActions([

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRewards::route('/'),
            'create' => Pages\CreateReward::route('/create'),
            'edit' => Pages\EditReward::route('/{record}/edit'),
        ];
    }
}
