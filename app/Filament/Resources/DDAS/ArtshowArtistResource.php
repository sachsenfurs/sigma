<?php

namespace App\Filament\Resources\DDAS;

use App\Filament\Resources\DDAS\ArtshowArtistResource\Pages;
use App\Filament\Resources\DDAS\ArtshowArtistResource\RelationManagers;
use App\Models\DDAS\ArtshowArtist;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ArtshowArtistResource extends Resource
{
    protected static ?string $model = ArtshowArtist::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Artshow';

    public static function getPluralLabel(): ?string
    {
        return __('Artshow Artists');
    }

    protected static ?int $navigationSort = 0;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Benutzername')
                    ->relationship('user', 'name'),
                Forms\Components\TextInput::make('name')
                    ->label('Künstlername')
                    ->maxLength(255),
                Forms\Components\TextInput::make('social')
                    ->label('Soziale Medien')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Benutzername')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Künstlername')
                    ->searchable(),
                Tables\Columns\TextColumn::make('social')
                    ->label('Soziale Medien')
                    ->searchable(),
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
            'index' => Pages\ListArtshowArtists::route('/'),
            'create' => Pages\CreateArtshowArtist::route('/create'),
            'edit' => Pages\EditArtshowArtist::route('/{record}/edit'),
        ];
    }
}
