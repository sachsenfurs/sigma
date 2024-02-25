<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SigEventResource\Pages;
use App\Filament\Resources\SigEventResource\RelationManagers;
use App\Models\SigEvent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SigEventResource extends Resource
{
    protected static ?string $model = SigEvent::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?string $navigationGroup = 'Sig';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('sig_host_id')
                    ->relationship('sigHost', 'name'),
                Forms\Components\Select::make('sig_location_id')
                    ->relationship('sigLocation', 'name')
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('default_language')
                    ->required()
                    ->maxLength(10)
                    ->default('de'),
                Forms\Components\TextInput::make('languages')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('reg_possible')
                    ->required(),
                Forms\Components\TextInput::make('max_regs_per_day')
                    ->numeric()
                    ->default(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sigHost.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sigLocation.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('default_language')
                    ->searchable(),
                Tables\Columns\IconColumn::make('reg_possible')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('max_regs_per_day')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\EditAction::make(),
                ]),
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

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSigEvents::route('/'),
            'create' => Pages\CreateSigEvent::route('/create'),
            'edit' => Pages\EditSigEvent::route('/{record}/edit'),
        ];
    }
}
