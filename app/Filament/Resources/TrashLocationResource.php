<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrashLocationResource\Pages;
use App\Filament\Resources\TrashLocationResource\RelationManagers;
use App\Models\TrashLocation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TrashLocationResource extends Resource
{
    protected static ?string $model = TrashLocation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('location_name')
                    ->label('Location Name')
                    ->required(),
                Forms\Components\TextInput::make('description')
                    ->label('Description')
                    ->required(),
                Forms\Components\Textarea::make('address')
                    ->label('Address')
                    ->columnSpanFull()
                    ->required(),
                    Forms\Components\FileUpload::make('location_image')
                    ->label('Location Image')
                    ->columnSpanFull()
                    ->directory('location_images')
                    ->image()
                    ->disk('public')
                    ->maxSize(2048)
                    ->preserveFilenames()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif']),
                    Forms\Components\TextInput::make('latitude')
                    ->label('Latitude')
                    ->numeric()
                    ->step(0.000000000000001)
                    ->required()
                    ->rules(['numeric', 'between:-90,90']), // Latitude must be between -90 and 90
                Forms\Components\TextInput::make('longitude')
                    ->label('Longitude')
                    ->numeric()
                    ->step(0.000000000000001)
                    ->required()
                    ->rules(['numeric', 'between:-180,180']),
                Forms\Components\Select::make('trash_category_id')
                    ->relationship('category', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('latitude'),
                Tables\Columns\TextColumn::make('longitude'),
                Tables\Columns\TextColumn::make('category.name'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrashLocations::route('/'),
            'create' => Pages\CreateTrashLocation::route('/create'),
            'edit' => Pages\EditTrashLocation::route('/{record}/edit'),
        ];
    }
}
