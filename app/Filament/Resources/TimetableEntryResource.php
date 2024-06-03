<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\SigPlanning;
use App\Filament\Resources\TimetableEntryResource\Pages;
use App\Filament\Resources\TimetableEntryResource\Widgets\TimeslotTable;
use App\Models\SigLocation;
use App\Models\TimetableEntry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Ramsey\Uuid\Type\Time;

class TimetableEntryResource extends Resource
{
    protected static ?string $model = TimetableEntry::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = SigPlanning::class;
    protected static ?int $navigationSort = 10;
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function can(string $action, ?Model $record = null): bool {
        return auth()->user()->permissions()->contains('manage_sigs');
    }

    public static function getLabel(): ?string {
        return __('Event Schedule');
    }

    public static function getPluralLabel(): ?string {
        return __('Event Schedule');
    }

    public static function form(Form $form): Form {
        return $form
            ->schema(self::getSchema());
    }

    public static function getSchema(): array {
        return [
            self::getSigEventField(),
            self::getSigLocationField(),
            self::getSigStartField(),
            self::getSigEndField(),

            Forms\Components\Fieldset::make("Event Settings")
                ->schema([
                    self::getSigNewField(),
                    self::getSigHideField(),
                    self::getSigCancelledField(),
                ])
                ->columns(3),

            Forms\Components\Fieldset::make("Communication Settings")
                ->schema([
                    self::getSendUpdateField(),
                ])
                ->hidden(fn(string $operation): bool => $operation == "create")
            ,

            self::getResetUpdateField(),
        ];
    }

    public static function table(Table $table): Table {
        return $table
            ->columns(static::getTableColumns())
            ->defaultPaginationPageOption('all')
            ->defaultGroup(
                Group::make('start')
                    ->label('')
                    ->collapsible()
                    ->getTitleFromRecordUsing(fn (Model $record) => Str::upper($record->start->dayName) . ', ' . $record->start->format('d.m.Y'))
            )
            ->filters([
                self::getLocationFilter(),
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

    public static function getRelations(): array {
        return [
            //
        ];
    }

    public static function getPages(): array {
        return [
            'index' => Pages\ListTimetableEntries::route('/'),
            'create' => Pages\CreateTimetableEntry::route('/create'),
            'edit' => Pages\EditTimetableEntry::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array {
        return [
            TimeslotTable::class,
        ];
    }

    public static function getTableColumns(): array {
        return [
            Tables\Columns\TextColumn::make('timestamp')
                ->getStateUsing(function ($record) {
                    $suffix = '';
                    if ($record->cancelled) {
                        $suffix = ' - ' . __('Cancelled');
                    } else {
                        if ($record->new)
                            $suffix = ' - ' . __('New');
                        if ($record->hasTimeChanged)
                            $suffix = ' - ' . __('Changed');
                    }
                    return $record->start->format('H:i') . ' - ' . $record->end->format('H:i') . $suffix;
                })
                ->badge(function (Model $record) {
                    return $record->cancelled || $record->new || $record->hasTimeChanged;
                })
                ->color(function (Model $record) {
                    if ($record->cancelled) {
                        return 'danger';
                    } else if ($record->new || $record->hasTimeChanged) {
                        return 'info';
                    }
                    return 'secondary';
                })
                ->label('Time span')
                ->width(10)
                ->translateLabel(),
            Tables\Columns\TextColumn::make('sigEvent.name')
                ->label('Event')
                ->translateLabel()
                ->searchable(),
            Tables\Columns\TextColumn::make('sigEvent.sigHost.name')
                 ->label('Host')
                 ->translateLabel()
                 ->searchable()
                 ->formatStateUsing(function (Model $record) {
                     $regNr = $record->sigEvent->sigHost->reg_id ? ' (' . __('Reg Number') . ': ' . $record->sigEvent->sigHost->reg_id . ')' : '';
                     return $record->sigEvent->sigHost->name . $regNr;
                 }),
            Tables\Columns\TextColumn ::make('sigLocation.name')
                ->badge()
                ->label('Location')
                ->translateLabel(),
            Tables\Columns\ImageColumn::make('sigEvent.languages')
                ->label('Languages')
                ->translateLabel()
                ->view('filament.tables.columns.sig-event.flag-icon'),
        ];
    }

    private static function getLocationFilter(): Tables\Filters\SelectFilter {
        return Tables\Filters\SelectFilter::make('sigLocation')
            ->label('Location')
            ->translateLabel()
            ->options(function (Model $record) {
                // If the location has a description, append it to the name
                if ($record->description) {
                    return $record->name . ' - ' . $record->description;
                }
                return $record->name;
            })
            ->getOptionLabelFromRecordUsing(function (Model $record) {
                // If the location has a description, append it to the name
                if ($record->description) {
                    return $record->name . ' - ' . $record->description;
                }
                return $record->name;
            })
            ->searchable()
            ->preload()
            ->relationship('sigLocation', 'name', fn (Builder $query) => $query->orderBy('name'));
    }

    public static function getSigEventField(): Forms\Components\Component {
        return Forms\Components\Select::make('sig_event_id')
            ->label('Event')
            ->translateLabel()
            ->autofocus(fn($state) => $state == null)
            ->model(TimetableEntry::class)
            ->relationship('sigEvent', 'name', fn (Builder $query) => $query->orderBy('name')->with('sigHost'))
            ->getOptionLabelFromRecordUsing(function (Model $record) {
                return $record->name . ' - ' . $record->sigHost->name;
            })
            ->required()
            ->searchable()
            ->preload();
    }

    public static function getSigLocationField(): Forms\Components\Component {
        return Forms\Components\Select::make('sig_location_id')
            ->label('Location')
            ->translateLabel()
            ->relationship('sigLocation', 'name')
            ->model(TimetableEntry::class)
            ->options(function(): array {
                return SigLocation::orderBy('name')->get()->mapWithKeys(function ($sigLocation) {
                    $name = $sigLocation->description ? $sigLocation->name . " - " . $sigLocation->description : $sigLocation->name;
                    return [ $sigLocation->id => $name ];
                })->toArray();
            })
            ->required()
            ->searchable()
            ->preload();
    }

    public static function getSigStartField(): Forms\Components\Component {
        return Forms\Components\DateTimePicker::make('start')
            ->label('Beginning')
            ->translateLabel()
            ->format('Y-m-d\TH:i')
            ->seconds(false)
            ->required();
    }

    public static function getSigEndField(): Forms\Components\Component {
        return Forms\Components\DateTimePicker::make('end')
            ->label('End')
            ->translateLabel()
            ->format('Y-m-d\TH:i')
            ->seconds(false)
            ->columns(1)
            ->required();
    }

    public static function getSigNewField(): Forms\Components\Component {
        return Forms\Components\Toggle::make('new')
            ->label('New Event')
            ->translateLabel()
            ->formatStateUsing(function(?Model $record) {
                // automatically prefill to "true" when con (in this case the first event) has started
                if(!$record) // only prefill when a new record is created (current $record == null)
                    return  Carbon::now()->isAfter(TimetableEntry::orderBy('start')->first()->start);
                return (bool)$record->new;
            });
    }

    public static function getSigCancelledField(): Forms\Components\Component {
        return Forms\Components\Toggle::make('cancelled')
            ->label('Event Cancelled')
            ->onColor("danger")
            ->visible(fn(?Model $record): bool => $record !== null AND ($record instanceof TimetableEntry))
            ->translateLabel();
    }

    public static function getSigHideField(): Forms\Components\Component {
        return Forms\Components\Toggle::make('hide')
            ->label('Internal Event')
            ->translateLabel();
    }

    public static function getResetUpdateField(): Forms\Components\Component {
        return Forms\Components\Checkbox::make('reset_update')
            ->model(TimetableEntry::class)
            ->label('Reset \'Changed\'-flag')
            ->translateLabel()
            ->visible(fn (string $operation, ?Model $record): bool => ($record?->hasEventChanged() ?? false))
            ;
    }

    public static function getSendUpdateField(): Forms\Components\Component {
        return Forms\Components\Checkbox::make('send_update')
            ->label('Announce Changes')
            ->translateLabel()
            ->helperText(__('This needs to be checked if the event should be marked as changed!'));
    }
}
