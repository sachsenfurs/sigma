<?php

namespace App\Filament\Resources\Ddas;

use App\Filament\Helper\FormHelper;
use App\Filament\Resources\Ddas\ArtshowPickupResource\Pages;
use App\Models\Ddas\ArtshowPickup;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ArtshowPickupResource extends Resource
{
    protected static ?string $model = ArtshowPickup::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';

    protected static ?int $navigationSort = 240;

    public static function getPluralLabel(): ?string {
        return __('Pickups');
    }
    public static function getNavigationGroup(): ?string {
        return __("Art Show");
    }

    public static function canAccess(): bool {
        return false;
    }


    public static function form(Form $form): Form {
        return $form
            ->schema([
                Select::make("user")
                    ->relationship("user", "name")
                    ->searchable(["reg_id", "name"])
                    ->columnSpanFull()
                    ->getOptionLabelFromRecordUsing(FormHelper::formatUserWithRegId()),
            ]);
    }

    public static function table(Table $table): Table {
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

    public static function getRelations(): array {
        return [
            //
        ];
    }

    public static function getPages(): array {
        return [
            'index' => Pages\ListArtshowPickups::route('/'),
            'create' => Pages\CreateArtshowPickup::route('/create'),
            'edit' => Pages\EditArtshowPickup::route('/{record}/edit'),
        ];
    }
}
