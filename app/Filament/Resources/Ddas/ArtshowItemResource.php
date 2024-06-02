<?php

namespace App\Filament\Resources\Ddas;

use App\Filament\Resources\Ddas\ArtshowItemResource\Pages;
use App\Filament\Resources\Ddas\ArtshowItemResource\RelationManagers;
use App\Models\Ddas\ArtshowItem;
use App\Models\Ddas\Enums\Approval;
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

    protected static ?int $navigationSort = 210;


    public static function can(string $action, ?Model $record = null): bool {
        return auth()->user()->permissions()->contains('manage_artshow');
    }

    public static function getPluralLabel(): ?string {
        return __('Art Show Items');
    }

    public static function getNavigationBadge(): ?string {
        return ArtshowItem::whereApproval(Approval::PENDING)->count() ?: null;
    }

    public static function form(Form $form): Form {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Item Name')
                    ->translateLabel()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->translateLabel()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('description_en')
                    ->label('Description (EN)')
                    ->translateLabel()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('starting_bid')
                    ->label('Starting Bid')
                    ->translateLabel()
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('charity_percentage')
                    ->label('Charity Percentage')
                    ->translateLabel()
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Textarea::make('additional_info')
                    ->label('Additional Informationen')
                    ->translateLabel()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('image')
                    ->label('Image')
                    ->translateLabel()
                    ->preserveFilenames(false)
                    ->disk('public')
                    ->image()
                    ->imageEditor()
                    ->maxFiles(1)
                    ->maxSize(5120),
                Forms\Components\Radio::make('approval')
                    ->label('Approval')
                    ->translateLabel()
                    ->default(Approval::PENDING)
                    ->options(Approval::class)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('artist.name')
                    ->label('Artist Name')
                    ->translateLabel()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Item Name')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('starting_bid')
                    ->label('Starting Bid')
                    ->translateLabel()
                    ->formatStateUsing(fn(string $state): string => "€ " . (int)$state)
                    ->sortable(),
                Tables\Columns\TextColumn::make('charity_percentage')
                    ->label('Charity Percentage')
                    ->translateLabel()
                    ->formatStateUsing(fn(string $state): string => (int)$state . " %")
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image')
                    ->translateLabel()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('approval')
                    ->label('Approval')
                    ->translateLabel()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('sold')
                    ->label('Sold')
                    ->translateLabel()
                    ->boolean()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('paid')
                    ->label('Paid')
                    ->translateLabel()
                    ->boolean()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([])
            ->headerActions([])
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
            'index' => Pages\ListArtshowItems::route('/'),
//            'create' => Pages\CreateArtshowItem::route('/create'),
            'edit' => Pages\EditArtshowItem::route('/{record}/edit'),
        ];
    }
}
