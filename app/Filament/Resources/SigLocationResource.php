<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SigLocationResource\Pages;
use App\Models\SigLocation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SigLocationResource extends Resource
{
    protected static ?string $model = SigLocation::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationGroup = 'SIG';
    protected static ?int $navigationSort = 60;

    public static function getLabel(): ?string {
        return __('Location');
    }

    public static function getPluralLabel(): ?string {
        return __('Locations');
    }

    public static function form(Form $form): Form {
        return $form
            ->schema([
                Forms\Components\Section::make(__("Location Details"))
                    ->collapsible()
                    ->collapsed(fn($operation) => $operation == "view")
                    ->schema([
                    self::getNameField(),
                    self::getNameEnField(),
                    self::getDescriptionField(),
                    self::getDescriptionEnField(),

                    Forms\Components\Fieldset::make()
                        ->label("Physical Information")
                        ->translateLabel()
                        ->columns(4)
                        ->schema([
                            self::getFloorField(),
                            self::getRoomField(),
                            self::getRoomSizeField(),
                            self::getSeatsField(),
                        ]),
                    Forms\Components\Fieldset::make()
                        ->columns(4)
                        ->label("Signage Info")
                        ->translateLabel()
                        ->schema([
                            self::getRenderIdField()->columnSpan(2),
                            self::getInfodisplayField(),
                            self::getEssentialField(),
                            Forms\Components\Grid::make()
                                 ->schema([
                                     self::getEssentialDescriptionField(),
                                     self::getEssentialDescriptionEnField(),
                                 ])
                        ]),
                ]),

            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns(self::getTableColumns())
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getRelations(): array {
        return [
            TimetableEntryResource\RelationManagers\TimetableEntriesRelationManager::class,
        ];
    }

    public static function getPages(): array {
        return [
            'index' => Pages\ListSigLocations::route('/'),
            'view' => Pages\ViewSigLocation::route('/{record}'),
            'create' => Pages\CreateSigLocation::route('/create'),
            'edit' => Pages\EditSigLocation::route('/{record}/edit'),
        ];
    }

    private static function getTableColumns(): array {
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

    private static function getNameField(): Forms\Components\Component {
        return Forms\Components\TextInput::make('name')
                                         ->label('Name')
                                         ->translateLabel()
                                         ->required()
                                         ->maxLength(255);
    }

    private static function getNameEnField(): Forms\Components\Component {
        return Forms\Components\TextInput::make('name_en')
                                         ->label('Name (English) (optional, defaults to Name)')
                                         ->translateLabel()
                                         ->nullable()
                                         ->maxLength(255);
    }

    private static function getDescriptionField(): Forms\Components\Component {
        return Forms\Components\TextInput::make('description')
                                         ->label('Functional Description ("Main Stage", ..)')
                                         ->nullable()
                                         ->translateLabel()
                                         ->maxLength(255);
    }

    private static function getDescriptionEnField(): Forms\Components\Component {
        return Forms\Components\TextInput::make('description_en')
                                         ->label('Functional Description (English)')
                                         ->nullable()
                                         ->translateLabel()
                                         ->maxLength(255);
    }

    private static function getRenderIdField(): Forms\Components\Component {
        return Forms\Components\TagsInput::make('render_ids');
    }

    private static function getFloorField(): Forms\Components\Component {
        return Forms\Components\TextInput::make('floor')
                                         ->label('Floor')
                                         ->translateLabel()
                                         ->nullable()
                                         ->maxLength(255);
    }

    private static function getRoomField(): Forms\Components\Component {
        return Forms\Components\TextInput::make('room')
                                         ->label('Room')
                                         ->translateLabel()
                                         ->nullable()
                                         ->maxLength(255);
    }

    private static function getInfodisplayField(): Forms\Components\Component {
        return Forms\Components\Toggle::make('infodisplay')
                                      ->label('Digital display in front of the door?')
                                      ->translateLabel()
                                      ->inline(false);
    }

    private static function getRoomSizeField(): Forms\Components\Component {
        return Forms\Components\TextInput::make('roomsize')
                                         ->label('Room Size')
                                         ->translateLabel()
                                         ->nullable()
                                         ->maxLength(255);
    }

    private static function getSeatsField(): Forms\Components\Component {
        return Forms\Components\TextInput::make('seats')
                                         ->label('Seats')
                                         ->translateLabel()
                                         ->nullable()
                                         ->maxLength(255);
    }

    private static function getEssentialDescriptionField(): Forms\Components\Component {
        return Forms\Components\Textarea::make('essential_description')
                                        ->label('Essential Description')
                                        ->translateLabel()
                                        ->nullable()
                                        ->rows(5);
    }

    private static function getEssentialDescriptionEnField(): Forms\Components\Component {
        return Forms\Components\Textarea::make('essential_description_en')
                                        ->label('Essential Description (English)')
                                        ->translateLabel()
                                        ->nullable()
                                        ->rows(5);
    }

    private static function getEssentialField(): Forms\Components\Component {
        return Forms\Components\Toggle::make("essential")
                                      ->label("Show in Essentials (Signage)")
                                      ->translateLabel()
                                      ->inline(false)
                                      ->default(false);
    }
}
