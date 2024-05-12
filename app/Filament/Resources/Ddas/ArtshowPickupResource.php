<?php

namespace App\Filament\Resources\Ddas;

use App\Filament\Resources\Ddas\ArtshowPickupResource\Pages;
use App\Models\Ddas\ArtshowPickup;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ArtshowPickupResource extends Resource
{
    protected static ?string $model = ArtshowPickup::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Artshow';

    public static function can(string $action, ?Model $record = null): bool
    {
        return auth()->user()->permissions()->contains('manage_artshow');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Art Show Pickups');
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
