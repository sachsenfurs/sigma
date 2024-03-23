<?php

namespace App\Filament\Resources\DDAS;

use App\Filament\Resources\DDAS\ArtshowItemResource\Pages;
use App\Filament\Resources\DDAS\ArtshowItemResource\RelationManagers;
use App\Models\DDAS\ArtshowItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ArtshowItemResource extends Resource
{
    protected static ?string $model = ArtshowItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Artshow';

    protected static ?int $navigationSort = 1;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('artshow_artist_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('name')
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('description_en')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('starting_bid')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('charity_percentage')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\Textarea::make('additional_info')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('image_file')
                    ->image(),
                Forms\Components\Toggle::make('approved')
                    ->required(),
                Forms\Components\Toggle::make('sold')
                    ->required(),
                Forms\Components\Toggle::make('paid')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('artshow_artist_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('starting_bid')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('charity_percentage')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image_file'),
                Tables\Columns\IconColumn::make('approved')
                    ->boolean()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('sold')
                    ->boolean()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('paid')
                    ->boolean()
                    ->toggleable(),
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
            'index' => Pages\ListArtshowItems::route('/'),
            'create' => Pages\CreateArtshowItem::route('/create'),
            'edit' => Pages\EditArtshowItem::route('/{record}/edit'),
        ];
    }
}
