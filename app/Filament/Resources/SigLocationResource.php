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

class SigLocationResource extends Resource
{
    protected static ?string $model = SigLocation::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationGroup = 'SIG';
    protected static ?int $navigationSort = 100;
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
                self::getNameField(),
                self::getDescriptionField(),
                self::getRenderIdField(),
                self::getFloorField(),
                self::getRoomField(),
                self::getInfodisplayField(),
                self::getRoomSizeField(),
                self::getSeatsField(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::getTableColumns())
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\SigEventsRelationManager::class,
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

    private static function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')
                ->label('Name')
                ->translateLabel()
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('description')
                ->label('Description')
                ->translateLabel()
                ->searchable(),
            Tables\Columns\TextColumn::make('floor')
                ->label('Floor')
                ->translateLabel()
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('room')
                ->label('Room')
                ->translateLabel()
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('sig_events_count')
                ->label('Event count')
                ->translateLabel()
                ->counts('sigEvents')
                ->sortable(),
            Tables\Columns\IconColumn::make('infodisplay')
                ->label('Infodisplay')
                ->translateLabel()
                ->boolean()
                ->sortable(),
        ];
    }

    private static function getNameField(): Forms\Components\Component
    {
        return Forms\Components\TextInput::make('name')
            ->label('Name')
            ->translateLabel()
            ->required()
            ->maxLength(255);
    }

    private static function getDescriptionField(): Forms\Components\Component
    {
        return Forms\Components\TextInput::make('description')
            ->label('Description')
            ->translateLabel()
            ->maxLength(255);
    }

    private static function getRenderIdField(): Forms\Components\Component
    {
        return Forms\Components\TextInput::make('render_ids');
    }

    private static function getFloorField(): Forms\Components\Component
    {
        return Forms\Components\TextInput::make('floor')
            ->label('Floor')
            ->translateLabel()
            ->maxLength(255);
    }

    private static function getRoomField(): Forms\Components\Component
    {
        return Forms\Components\TextInput::make('room')
            ->label('Room')
            ->translateLabel()
            ->maxLength(255);
    }

    private static function getInfodisplayField(): Forms\Components\Component
    {
        return Forms\Components\Toggle::make('infodisplay')
            ->label('Digital display in front of the door?')
            ->translateLabel()
            ->inline(false);
    }

    private static function getRoomSizeField(): Forms\Components\Component
    {
        return Forms\Components\TextInput::make('roomsize')
            ->label('Room Size')
            ->translateLabel()
            ->maxLength(255);
    }

    private static function getSeatsField(): Forms\Components\Component
    {
        return Forms\Components\TextInput::make('seats')
            ->label('Seats')
            ->translateLabel()
            ->maxLength(255);
    }
}
