<?php

namespace App\Filament\Resources\DDAS;

use App\Filament\Resources\DDAS\ArtshowBidResource\Pages;
use App\Filament\Resources\DDAS\ArtshowBidResource\RelationManagers;
use App\Models\DDAS\ArtshowBid;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ArtshowBidResource extends Resource
{
    protected static ?string $model = ArtshowBid::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Artshow';

    public static function getPluralLabel(): ?string
    {
        return __('Artshow Bids');
    }

    protected static ?int $navigationSort = 2;
    
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
            'index' => Pages\ListArtshowBids::route('/'),
            'create' => Pages\CreateArtshowBid::route('/create'),
            'edit' => Pages\EditArtshowBid::route('/{record}/edit'),
        ];
    }
}
