<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Actions\EditAction;
use App\Filament\Resources\TimetableEntryResource\RelationManagers\TimetableEntriesRelationManager;
use App\Filament\Resources\SigLocationResource\Pages\ListSigLocations;
use App\Filament\Resources\SigLocationResource\Pages\CreateSigLocation;
use App\Filament\Resources\SigLocationResource\Pages\ViewSigLocation;
use App\Filament\Resources\SigLocationResource\Pages\EditSigLocation;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Schemas\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use App\Filament\Resources\SigLocationResource\Pages;
use App\Filament\Traits\HasActiveIcon;
use App\Models\SigLocation;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SigLocationResource extends Resource
{
    use HasActiveIcon;
    protected static ?string $model = SigLocation::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-office';

    protected static string | \UnitEnum | null $navigationGroup = 'SIG';
    protected static ?int $navigationSort = 60;

    public static function getLabel(): ?string {
        return __('Location');
    }

    public static function getPluralLabel(): ?string {
        return __('Locations');
    }

    public static function form(Schema $schema): Schema {
        return $schema
            ->components([
                Section::make(__("Location Details"))
                    ->collapsible()
                    ->collapsed(fn($operation) => $operation == "view")
                    ->columnSpanFull()
                    ->schema([
                    self::getNameField(),
                    self::getNameEnField(),
                    self::getDescriptionField(),
                    self::getDescriptionEnField(),

                    Fieldset::make()
                        ->label("Physical Information")
                        ->translateLabel()
                        ->columnSpanFull()
                        ->columns(4)
                        ->schema([
                            self::getFloorField(),
                            self::getRoomField(),
                            self::getRoomSizeField(),
                            self::getSeatsField(),
                        ]),
                    Fieldset::make()
                        ->columns(4)
                        ->columnSpanFull()
                        ->label("Signage Info")
                        ->translateLabel()
                        ->schema([
                            self::getRenderIdField()->columnSpan(2),
                            self::getInfodisplayField(),
                            self::getEssentialField(),
                            Grid::make()
                                 ->columnSpanFull()
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
            ->recordActions([
                EditAction::make(),
            ]);
    }

    public static function getRelations(): array {
        return [
            TimetableEntriesRelationManager::class,
        ];
    }

    public static function getPages(): array {
        return [
            'index' => ListSigLocations::route('/'),
            'create' => CreateSigLocation::route('/create'),
            'view' => ViewSigLocation::route('/{record}'),
            'edit' => EditSigLocation::route('/{record}/edit'),
        ];
    }

    private static function getTableColumns(): array {
        return [
            TextColumn::make('name_localized')
                 ->label('Name')
                 ->translateLabel()
                 ->searchable(['name', 'name_en'])
                 ->sortable(query: function(Builder $query, string $direction) {
                     return $query
                         ->selectRaw("COALESCE(`name_en`, `name`) AS name_en2")
                         ->orderBy(app()->getLocale() == "en" ? 'name_en2' : 'name', $direction);
                 }),
            TextColumn::make('description_localized')
                 ->label('Description')
                 ->translateLabel()
                 ->searchable(['description', 'description_en']),
            TextColumn::make('floor')
                 ->label('Floor')
                 ->translateLabel()
                 ->searchable()
                 ->sortable(),
            TextColumn::make('room')
                 ->label('Room')
                 ->translateLabel()
                 ->searchable()
                 ->sortable(),
            TextColumn::make('sig_events_count')
                 ->label('Event count')
                 ->translateLabel()
                 ->counts('sigEvents')
                 ->sortable(),
            IconColumn::make("essential")
                ->label("Essential")
                ->translateLabel()
                ->boolean()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            IconColumn::make('infodisplay')
                 ->label('Info Display')
                 ->translateLabel()
                 ->boolean()
                 ->sortable(),
        ];
    }

    private static function getNameField(): Component {
        return TextInput::make('name')
             ->label('Name')
             ->translateLabel()
             ->required()
             ->maxLength(255);
    }

    private static function getNameEnField(): Component {
        return TextInput::make('name_en')
             ->label('Name (English) (optional, defaults to Name)')
             ->translateLabel()
             ->nullable()
             ->maxLength(255);
    }

    private static function getDescriptionField(): Component {
        return TextInput::make('description')
             ->label('Functional Description ("Main Stage", ..) used for Signage etc.')
             ->nullable()
             ->translateLabel()
             ->maxLength(255);
    }

    private static function getDescriptionEnField(): Component {
        return TextInput::make('description_en')
             ->label('Functional Description (English)')
             ->nullable()
             ->translateLabel()
             ->maxLength(255);
    }

    private static function getRenderIdField(): Component {
        return TagsInput::make('render_ids');
    }

    private static function getFloorField(): Component {
        return TextInput::make('floor')
             ->label('Floor')
             ->translateLabel()
             ->nullable()
             ->maxLength(255);
    }

    private static function getRoomField(): Component {
        return TextInput::make('room')
             ->label('Room')
             ->translateLabel()
             ->nullable()
             ->maxLength(255);
    }

    private static function getInfodisplayField(): Component {
        return Toggle::make('infodisplay')
             ->label('Digital display in front of the door?')
             ->translateLabel()
             ->inline(false);
    }

    private static function getRoomSizeField(): Component {
        return TextInput::make('roomsize')
            ->label('Room Size')
            ->translateLabel()
            ->nullable()
            ->maxLength(255);
    }

    private static function getSeatsField(): Component {
        return TextInput::make('seats')
            ->label('Seats')
            ->translateLabel()
            ->nullable()
            ->maxLength(255);
    }

    private static function getEssentialDescriptionField(): Component {
        return Textarea::make('essential_description')
            ->label('Essential Description')
            ->translateLabel()
            ->nullable()
            ->rows(5);
    }

    private static function getEssentialDescriptionEnField(): Component {
        return Textarea::make('essential_description_en')
            ->label('Essential Description (English)')
            ->translateLabel()
            ->nullable()
            ->rows(5);
    }

    private static function getEssentialField(): Component {
        return Toggle::make("essential")
            ->label("Show in Essentials (Signage)")
            ->translateLabel()
            ->inline(false)
            ->default(false);
    }
}
