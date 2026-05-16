<?php

namespace App\Filament\Resources\SigTimeslots;

use BackedEnum;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Schemas\Schema;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\SigTimeslots\RelationManagers\SigAttendeeRelationManager;
use App\Filament\Resources\SigTimeslots\Pages\ListSigTimeslots;
use App\Filament\Resources\SigTimeslots\Pages\ViewSigTimeslot;
use App\Filament\Resources\SigTimeslots\Pages\EditSigTimeslot;
use App\Filament\Clusters\SigManagement\SigManagementCluster;
use App\Filament\Traits\HasActiveIcon;
use App\Models\SigEvent;
use App\Models\SigTimeslot;
use App\Models\TimetableEntry;
use App\Settings\AppSettings;
use Carbon\Carbon;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Support\Icons\Heroicon;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class SigTimeslotResource extends Resource
{
    use HasActiveIcon;
    protected static ?string $model = SigTimeslot::class;
    protected static ?string $cluster = SigManagementCluster::class;
    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedClock;
    protected static ?int $navigationSort = 10;
    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getModelLabel(): string {
        return __("Time Slot");
    }

    public static function getPluralModelLabel(): string {
        return __("Time Slots");
    }

    public static function form(Schema $schema): Schema {
        return $schema
            ->components(self::getFormComponents());
    }

    public static function table(Table $table): Table {
        return $table
            ->columns([
                IconColumn::make("reg_possible")
                    ->label("Registration possible")
                    ->translateLabel()
                    ->wrapHeader()
                    ->width(1)
                    ->boolean()
                    ->alignCenter()
                    ->default(fn(SigTimeslot $record) =>
                        $record->sig_attendees_count < $record->max_users
                        AND $record->reg_end->isAfter(now())
                    ),
                TextColumn::make('slot_start')
                    ->dateTime("H:i")
                    ->label("Slot Start")
                    ->translateLabel()
                    ->alignCenter(),
                TextColumn::make('slot_end')
                    ->dateTime("H:i")
                    ->label("Slot End")
                    ->translateLabel()
                    ->alignCenter(),
                TextColumn::make('sig_attendees_count')
                    ->counts("sigAttendees")
                    ->formatStateUsing(fn(string $state, Model $record) => $state . " / " . $record->max_users)
                    ->label("Attendee Count")
                    ->translateLabel()
                    ->alignCenter(),
                TextColumn::make("sigAttendees.user.name")
                    ->listWithLineBreaks()
                    ->limitList()
                    ->action(
                        ViewAction::make("attendee_list")
                            ->recordTitle(__("Attendees"))
                            ->schema([
                                RepeatableEntry::make("sigAttendees")
                                    ->label("")
                                    ->schema([
                                        TextEntry::make("user.name")
                                            ->label("")
                                            ->columns(1)
                                            ->inlineLabel()
                                            ->formatStateUsing(fn($record) => $record->user->reg_id . " - " . $record->user->name),
                                        TextEntry::make("created_at")
                                            ->label("Registered at")
                                            ->translateLabel()
                                            ->dateTime()
                                            ->inlineLabel()
                                            ->columns(1)
                                    ])
                                    ->columns(2)
                            ])
                    )
                    ->tooltip(fn($record) => $record->sigAttendees->pluck("user.name")->join(", ")),
                IconColumn::make("group_registration")
                    ->label(__("Group Registration"))
                    ->boolean(),
                TextColumn::make('reg_start')
                    ->label("Registration Start")
                    ->translateLabel()
                    ->dateTime()
                    ->badge()
                    ->toggleable()
                    ->color(fn($record) => $record->reg_start->isBefore(now()) ? Color::Green : Color::Neutral)
                    ->sortable(),
                TextColumn::make('reg_end')
                    ->label("Registration End")
                    ->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable()
                    ->color(fn($record) => $record->reg_end->isAfter(now()) ? Color::Neutral : Color::Red)
                    ->badge(),
                TextColumn::make("notes")
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->listWithLineBreaks()
                    ->limit(100)
                    ->label("Notes")
                    ->translateLabel(),
            ])
            ->defaultGroup(
                Group::make("timetableEntry.sigEvent.id")
                    ->collapsible()
                    ->orderQueryUsing(fn(Builder $query) => $query
                        ->join("timetable_entries", "sig_timeslots.timetable_entry_id", "=", "timetable_entries.id")
                        ->orderBy("timetable_entries.start")
                    )
                    ->label("")
                    ->getTitleFromRecordUsing(fn($record) =>
                        $record->timetableEntry->sigEvent->name_localized . " | "
                        . $record->timetableEntry->start->translatedFormat("l d.m.y | H:i") . " - "
                        . $record->timetableEntry->end->format("H:i")),
            )
            ->filters([
                SelectFilter::make("sigEvent")
                    ->relationship("timetableEntry.sigEvent", "name", fn(Builder $query) => $query->whereHas("sigTimeslots"))
                    ->searchable()
                    ->preload()
                    ->label("Event")
                    ->translateLabel(),
            ])
            ->defaultSort("slot_start")
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array {
        return [
            SigAttendeeRelationManager::class,
        ];
    }

    public static function getPages(): array {
        return [
            'index' => ListSigTimeslots::route('/'),
            'view' => ViewSigTimeslot::route('/{record}'),
            'edit' => EditSigTimeslot::route('/{record}/edit'),
        ];
    }

    public static function getSubHeading(Model $record): string|Htmlable {
        return new HtmlString($record->slot_start->translatedFormat("l, d.m.Y")
        . "<br>"
        . $record->sigAttendees->count() . " / " . $record->max_users . " " . __("Attendees"));
    }

    public static function getHeading(Model $record): string|Htmlable {
        return $record->slot_start->format("H:i") . " - " . $record->slot_end->format("H:i") . " | " . $record->timetableEntry->sigEvent->name_localized;
    }

    public static function getFormComponents(): array {
        return [
            Select::make("timetable_entry_id")
                ->label(__("Timetable Entry"))
                ->translateLabel()
                ->columnSpanFull()
                ->selectablePlaceholder(false)
                ->model(TimetableEntry::class)
                ->options(fn ($livewire) => self::getAvailableEntries($livewire)
                    ->loadMissing('sigEvent')
                    ->keyBy('id')
                    ->map(fn (TimetableEntry $entry) => $entry->start->translatedFormat("l d.m.Y, H:i") . " - " . $entry->end->translatedFormat("H:i") . " | " . $entry->sigEvent->name_localized)
                    ->all())
                ->default(fn ($livewire) => self::getDefaultEntryId($livewire))
                ->live()
                ->required()
                ->disabled(fn ($livewire) => data_get($livewire, 'ownerRecord') instanceof TimetableEntry),
            DateTimePicker::make('slot_start')
                ->label('Slot Start')
                ->translateLabel()
                ->seconds(false)
                ->live()
                ->hintAction(fn ($livewire) => self::makeSetToEntryBoundaryAction(
                    $livewire,
                    'slot_start',
                    'Set to SIG Start',
                    fn (TimetableEntry $entry) => $entry->start,
                ))
                ->afterOrEqual(fn (Get $get, $livewire) => self::getCurrentEntry($livewire, self::getSelectedEntryId($livewire, $get('timetable_entry_id')))?->start)
                ->beforeOrEqual(fn (Get $get, $livewire) => self::getCurrentEntry($livewire, self::getSelectedEntryId($livewire, $get('timetable_entry_id')))?->end)
                ->default(function (Get $get, $livewire): ?Carbon {
                    if(($record = data_get($livewire, 'record')) instanceof SigTimeslot)
                        return $record->slot_start;

                    $entryId = self::getSelectedEntryId($livewire, $get('timetable_entry_id'));

                    if($previousSlot = self::getPreviousSlot($livewire, $entryId))
                        return $previousSlot->slot_end;

                    return self::getCurrentEntry($livewire, $entryId)?->start;
                })
                ->afterStateUpdated(function (?string $state, Set $set, Get $get) {
                    if(!$state || !$get('slot_end'))
                        return;

                    if(Carbon::parse($state)->toDateString() !== Carbon::parse($get('slot_end'))->toDateString())
                        $set('slot_end', Carbon::parse($state)->addMinutes(15)->format("Y-m-d\TH:i"));
                })
                ->lazy()
                ->required(),
            DateTimePicker::make('slot_end')
                ->label('Slot End')
                ->translateLabel()
                ->seconds(false)
                ->hintAction(fn ($livewire) => self::makeSetToEntryBoundaryAction(
                    $livewire,
                    'slot_end',
                    'Set to SIG End',
                    fn (TimetableEntry $entry) => $entry->end,
                ))
                ->afterOrEqual('slot_start')
                ->beforeOrEqual(fn (Get $get, $livewire) => self::getCurrentEntry($livewire, self::getSelectedEntryId($livewire, $get('timetable_entry_id')))?->end)
                ->live()
                ->default(function (Get $get, $livewire): ?Carbon {
                    if(($record = data_get($livewire, 'record')) instanceof SigTimeslot)
                        return $record->slot_end;

                    $entryId = self::getSelectedEntryId($livewire, $get('timetable_entry_id'));

                    if($previousSlot = self::getPreviousSlot($livewire, $entryId)) {
                        $length = $previousSlot->slot_start->diffInMinutes($previousSlot->slot_end);
                        return $previousSlot->slot_end->copy()->addMinutes($length);
                    }

                    if($slotStart = $get('slot_start'))
                        return Carbon::parse($slotStart)->addMinutes(15);

                    return self::getCurrentEntry($livewire, $entryId)?->start?->copy()->addMinutes(15);
                })
                ->required(),
            DateTimePicker::make('reg_start')
                ->label('Registration Start')
                ->translateLabel()
                ->seconds(false)
                ->hintAction(function () {
                    return Action::make("setToConStart")
                        ->label("Set to Con Start")
                        ->translateLabel()
                        ->action(function (Set $set) {
                            $set('reg_start', app(AppSettings::class)->event_start->format("Y-m-d\TH:i"));
                        });
                })
                ->default(fn (Get $get, $livewire) => self::getPreviousSlot($livewire, self::getSelectedEntryId($livewire, $get('timetable_entry_id')))?->reg_start ?? app(AppSettings::class)->event_start),
            DateTimePicker::make('reg_end')
                ->label('Registration End')
                ->translateLabel()
                ->seconds(false)
                ->hintAction(fn ($livewire) => self::makeSetToEntryBoundaryAction(
                    $livewire,
                    'reg_end',
                    'Set to SIG Start',
                    fn (TimetableEntry $entry) => $entry->start,
                ))
                ->default(fn (Get $get, $livewire) => self::getPreviousSlot($livewire, self::getSelectedEntryId($livewire, $get('timetable_entry_id')))?->reg_end ?? app(AppSettings::class)->event_end),
            TextInput::make('max_users')
                ->label('Max. Attendees')
                ->translateLabel()
                ->type('number')
                ->minValue(1)
                ->default(fn (Get $get, $livewire) => self::getPreviousSlot($livewire, self::getSelectedEntryId($livewire, $get('timetable_entry_id')))?->max_users ?? 1),
            Grid::make()
                ->columns(2)
                ->columnSpanFull()
                ->schema([
                    Checkbox::make('self_register')
                        ->inline(false)
                        ->default(fn (Get $get, $livewire) => self::getPreviousSlot($livewire, self::getSelectedEntryId($livewire, $get('timetable_entry_id')))?->self_register ?? true),
                    Checkbox::make('group_registration')
                        ->label(__("Group Registration"))
                        ->helperText(__("Allow the first registered attendee to manage (Add/Remove) attendees for his timeslot"))
                        ->inline(false)
                        ->default(fn (Get $get, $livewire) => self::getPreviousSlot($livewire, self::getSelectedEntryId($livewire, $get('timetable_entry_id')))?->group_registration ?? true),
                ]),
        ];
    }

    private static function getAvailableEntries(mixed $livewire): Collection {
        if(($entries = data_get($livewire, 'entries')) instanceof Collection)
            return $entries;

        if(($record = data_get($livewire, 'record')) instanceof SigTimeslot)
            return $record->timetableEntry->sigEvent->timetableEntries;

        if(($ownerRecord = data_get($livewire, 'ownerRecord')) instanceof TimetableEntry)
            return $ownerRecord->sigEvent->timetableEntries;

        if(($ownerRecord = data_get($livewire, 'ownerRecord')) instanceof SigEvent)
            return $ownerRecord->timetableEntries;

        return TimetableEntry::query()
            ->with('sigEvent')
            ->orderBy('start')
            ->get();
    }

    private static function getCurrentEntry(mixed $livewire, mixed $entryId = null): ?TimetableEntry {
        if(!filled($entryId))
            return null;

        return self::getAvailableEntries($livewire)->firstWhere('id', (int) $entryId)
            ?? TimetableEntry::find((int) $entryId);
    }

    private static function getSelectedEntryId(mixed $livewire, mixed $entryId = null): ?int {
        return filled($entryId)
            ? (int) $entryId
            : self::getDefaultEntryId($livewire);
    }

    private static function getPreviousSlot(mixed $livewire, mixed $entryId = null): ?SigTimeslot {
        if(data_get($livewire, 'record') instanceof SigTimeslot)
            return null;

        $entry = self::getCurrentEntry($livewire, $entryId);

        if(!$entry)
            return null;

        $entry->loadMissing('sigTimeslots');
        return $entry->sigTimeslots->sortBy('slot_end')->last();
    }

    private static function getDefaultEntryId(mixed $livewire): ?int {
        if(($record = data_get($livewire, 'record')) instanceof SigTimeslot)
            return $record->timetable_entry_id;

        if(($ownerRecord = data_get($livewire, 'ownerRecord')) instanceof TimetableEntry)
            return $ownerRecord->id;

        if(($ownerRecord = data_get($livewire, 'ownerRecord')) instanceof SigEvent)
            return self::getAvailableEntries($livewire)->first()?->id;

        return null;
    }

    private static function makeSetToEntryBoundaryAction(mixed $livewire, string $field, string $label, callable $resolver, mixed $entryId = null): Action {
        return Action::make($field)
            ->label($label)
            ->translateLabel()
            ->disabled(fn (Get $get) => !self::getCurrentEntry(
                $livewire,
                self::getSelectedEntryId($livewire, $entryId ?? $get('timetable_entry_id')),
            ))
            ->action(function (Set $set, Get $get) use ($livewire, $field, $resolver, $entryId) {
                $entry = self::getCurrentEntry(
                    $livewire,
                    self::getSelectedEntryId($livewire, $entryId ?? $get('timetable_entry_id')),
                );

                if(!$entry)
                    return;

                $set($field, $resolver($entry)->format("Y-m-d\TH:i"));
            });
    }
}
