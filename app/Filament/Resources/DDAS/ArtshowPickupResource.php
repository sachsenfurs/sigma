<?php

namespace App\Filament\Resources\DDAS;

use App\Filament\Resources\DDAS\ArtshowPickupResource\Pages;
use App\Filament\Resources\DDAS\ArtshowPickupResource\RelationManagers;
use App\Models\DDAS\ArtshowPickup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ArtshowPickupResource extends Resource
{
    protected static ?string $model = ArtshowPickup::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Artshow';

    public static function getPluralLabel(): ?string
    {
        return __('Artshow Pickups');
    }

    protected static ?int $navigationSort = 240;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListArtshowPickups::route('/'),
            'create' => Pages\CreateArtshowPickup::route('/create'),
            'edit' => Pages\EditArtshowPickup::route('/{record}/edit'),
        ];
    }
}
