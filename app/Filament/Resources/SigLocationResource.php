<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SigLocationResource\Pages;
use App\Filament\Resources\SigLocationResource\RelationManagers;
use App\Models\SigLocation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SigLocationResource extends Resource
{
    protected static ?string $model = SigLocation::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationGroup = 'SIG';

    public static function getLabel(): ?string
    {
        return __('Location');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Locations');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('render_ids'),
                Forms\Components\TextInput::make('floor')
                    ->maxLength(255),
                Forms\Components\TextInput::make('room')
                    ->maxLength(255),
                Forms\Components\Toggle::make('infodisplay')
                    ->required(),
                Forms\Components\TextInput::make('roomsize')
                    ->maxLength(255),
                Forms\Components\TextInput::make('seats')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('floor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('room')
                    ->searchable(),
                Tables\Columns\IconColumn::make('infodisplay')
                    ->boolean(),
                Tables\Columns\TextColumn::make('roomsize')
                    ->searchable(),
                Tables\Columns\TextColumn::make('seats')
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
            'index' => Pages\ListSigLocations::route('/'),
            'create' => Pages\CreateSigLocation::route('/create'),
            'edit' => Pages\EditSigLocation::route('/{record}/edit'),
        ];
    }
}
