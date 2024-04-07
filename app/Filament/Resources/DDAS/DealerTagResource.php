<?php

namespace App\Filament\Resources\DDAS;

use App\Filament\Resources\DDAS\DealerTagResource\Pages;
use App\Filament\Resources\DDAS\DealerTagResource\RelationManagers;
use App\Models\DDAS\DealerTag;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DealerTagResource extends Resource
{
    protected static ?string $model = DealerTag::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Dealer\'s Den';

    protected static ?int $navigationSort = 320;


    public static function getPluralLabel(): ?string
    {
        return __('Dealer Tags');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Tag Name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name_en')
                    ->label('Tag Name (EN)')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name_en')
                    ->searchable()
                    ->sortable(),
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
            'index' => Pages\ListDealerTags::route('/'),
            'create' => Pages\CreateDealerTag::route('/create'),
            'edit' => Pages\EditDealerTag::route('/{record}/edit'),
        ];
    }
}
