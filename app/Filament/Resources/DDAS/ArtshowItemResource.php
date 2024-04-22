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
use Illuminate\Database\Eloquent\Model;

class ArtshowItemResource extends Resource
{
    protected static ?string $model = ArtshowItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Artshow';

    public static function can(string $action, ?Model $record = null): bool
    {
        return auth()->user()->permissions()->contains('manage_artshow');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Artshow Items');
    }

    protected static ?int $navigationSort = 230;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('artshow_artist_id')
                    ->label('Künstler ID')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('name')
                    ->label('Gegenstandsname')
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('Beschreibung')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('description_en')
                    ->label('Beschreibung (EN)')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('starting_bid')
                    ->label('Startgebot')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('charity_percentage')
                    ->label('Charity-Prozentsatz')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\Textarea::make('additional_info')
                    ->label('Zusätzliche Informationen')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('image_file')
                    ->label('Bild')
                    ->required()
                    ->disk('public')
                    ->image()
                    ->imageEditor()
                    ->maxFiles(1)
                    ->preserveFilenames()
                    ->maxSize(5120),
                Forms\Components\Toggle::make('approved')
                    ->label('Genehmigt')
                    ->required(),
                Forms\Components\Toggle::make('sold')
                    ->label('Verkauft')
                    ->required(),
                Forms\Components\Toggle::make('paid')
                    ->label('Bezahlt')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('artshow_artist_id')
                    ->label('Künstler ID')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Gegenstandsname')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('starting_bid')
                    ->label('Startgebot')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('charity_percentage')
                    ->label('Charity-Prozentsatz')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image_file')
                    ->label('Bild')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('approved')
                    ->label('Genehmigt')
                    ->boolean()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('sold')
                    ->label('Verkauft')
                    ->boolean()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('paid')
                    ->label('Bezahlt')
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
