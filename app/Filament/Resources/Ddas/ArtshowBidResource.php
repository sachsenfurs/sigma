<?php

namespace App\Filament\Resources\Ddas;

use App\Filament\Resources\Ddas\ArtshowBidResource\Pages;
use App\Models\Ddas\ArtshowBid;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ArtshowBidResource extends Resource
{
    protected static ?string $model = ArtshowBid::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-euro';

    protected static ?string $navigationGroup = 'Artshow';

    protected static ?int $navigationSort = 220;

    public static function can(string $action, ?Model $record = null): bool {
        return auth()->user()->permissions()->contains('manage_artshow');
    }

    public static function getPluralLabel(): ?string {
        return __('Art Show Bids');
    }

    public static function form(Form $form): Form {
        return $form
            ->schema([
                //
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
            'index' => Pages\ListArtshowBids::route('/'),
            'create' => Pages\CreateArtshowBid::route('/create'),
            'edit' => Pages\EditArtshowBid::route('/{record}/edit'),
        ];
    }
}
