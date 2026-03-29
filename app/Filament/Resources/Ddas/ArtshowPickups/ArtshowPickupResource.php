<?php

namespace App\Filament\Resources\Ddas\ArtshowPickups;

use BackedEnum;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Ddas\ArtshowPickups\Pages\ListArtshowPickups;
use App\Filament\Resources\Ddas\ArtshowPickups\Pages\CreateArtshowPickup;
use App\Filament\Resources\Ddas\ArtshowPickups\Pages\EditArtshowPickup;
use App\Filament\Helper\FormHelper;
use App\Models\Ddas\ArtshowPickup;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ArtshowPickupResource extends Resource
{
    protected static ?string $model = ArtshowPickup::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedGift;

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


    public static function form(Schema $schema): Schema {
        return $schema
            ->components([
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
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => ListArtshowPickups::route('/'),
            'create' => CreateArtshowPickup::route('/create'),
            'edit' => EditArtshowPickup::route('/{record}/edit'),
        ];
    }
}
