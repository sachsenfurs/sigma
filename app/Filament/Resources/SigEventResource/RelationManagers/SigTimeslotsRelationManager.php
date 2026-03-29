<?php

namespace App\Filament\Resources\SigEventResource\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Grouping\Group;
use Filament\Actions\CreateAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Width;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkAction;
use Filament\Actions\Action;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Grid;
use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Filament\Resources\SigTimeslotResource;
use App\Models\SigEvent;
use App\Models\SigTimeslot;
use App\Models\TimetableEntry;
use App\Settings\AppSettings;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class SigTimeslotsRelationManager extends RelationManager
{
    public ?Collection $entries;
    public ?TimetableEntry $entry = null;
    public ?SigEvent $sigEvent = null;

    protected static string $relationship = 'sigTimeslots';
    protected static string | \BackedEnum | null $icon = 'heroicon-o-clock';

    public static function getTitle(Model $ownerRecord, string $pageClass): string {
        return __("Time Slots");
    }

    public function mount(): void {
        parent::mount();
        if($this->ownerRecord instanceof SigEvent) {
            $this->entries      = $this->ownerRecord->timetableEntries;
            $this->sigEvent     = $this->ownerRecord;
            $this->entry        = $this->entries->first();
        }
        if($this->ownerRecord instanceof TimetableEntry) {
            $this->entries      = $this->ownerRecord->sigEvent->timetableEntries;
            $this->sigEvent     = $this->ownerRecord->sigEvent;
            $this->entry        = $this->ownerRecord;
        }
    }

    protected function getTableHeading(): string|Htmlable|null {
        return __("Time Slots");
    }

    public static function getModelLabel(): ?string {
        return __("Time Slot");
    }
    public static function getPluralLabel(): ?string {
        return __("Time Slots");
    }

    public function isReadOnly(): bool {
        return !auth()->user()->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE);
    }

    public function form(Schema $schema): Schema {
        return $schema
            ->components($this->getTimeslotForm());
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string {
        return $ownerRecord->sigTimeslots->count() ?: null;
    }

    public function table(Table $table): Table {
        return $table
            ->columns([
                TextColumn::make("timetableEntry")
                    ->dateTime()
                    ->formatStateUsing(fn($state) => $state->start->translatedFormat("H:i") . " - ". $state->end->translatedFormat("H:i"))
                    ->label(__("Timetable Entry"))
                    ->translateLabel(),
                IconColumn::make("self_register")
                    ->boolean(),
                IconColumn::make("group_registration")
                    ->label(__("Group Registration"))
                    ->boolean(),
                TextColumn::make('slot_start')
                    ->label('Slot Start')
                    ->translateLabel()
                    ->dateTime('H:i'),
                TextColumn::make('slot_end')
                    ->label('Slot End')
                    ->translateLabel()
                    ->dateTime('H:i'),
                TextColumn::make('reg_start')
                    ->label('Registration Start')
                    ->translateLabel()
                    ->dateTime(),
                TextColumn::make('reg_end')
                    ->label('Registration End')
                    ->translateLabel()
                    ->dateTime(),
                TextColumn::make('max_users')
                    ->label('Attendees')
                    ->translateLabel()
                    ->formatStateUsing(function (SigTimeslot $timeslot) {
                        return $timeslot->sigAttendees->count() . '/' . $timeslot->max_users;
                    })
                    ->tooltip(fn($record) => $record->sigAttendees->pluck("user.name")->join(", ")),
            ])
            ->recordUrl(fn($record) => SigTimeslotResource::getUrl("view", ['record' => $record]))
            ->defaultGroup(
                Group::make("timetableEntry.start")
                    ->collapsible()
                    ->label('')
                    ->date(),
            )
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->disabled($this->entries->count() == 0)
            ])
            ->recordActions([
                ViewAction::make('view')
                    ->label('Attendees')
                    ->translateLabel()
                    ->icon('heroicon-s-users')
                    ->modalWidth(Width::Medium)
                    ->modalHeading(__('Attendee List'))
                    ->schema([
                        RepeatableEntry::make('sigAttendees')
                            ->label('')
                            ->schema([
                                TextEntry::make('user.name')
                                    ->label('')
                                    ->formatStateUsing(function ($record) {
                                        return $record->user->name . ' (' . __('Reg Number') . ': ' . ($record->user->reg_id ?? 'N/A') . ')';
                                    }),
                            ])
                    ]),
                EditAction::make(),
                DeleteAction::make()
                    ->label(""),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make("edit")
                        ->label("Edit")
                        ->translateLabel()
                        ->icon("heroicon-o-pencil-square")
                        ->schema([
                            DateTimePicker::make('reg_start')
                                 ->label('Registration Start')
                                 ->translateLabel()
                                 ->seconds(false)
                                 ->hintAction(
                                     function() {
                                         if($this->entry) {
                                             return Action::make("setToConStart")
                                                ->label("Set to Con Start")
                                                ->translateLabel()
                                                ->action(function (Set $set) {
                                                   $set('reg_start', app(AppSettings::class)->event_start->format("Y-m-d\TH:i"));
                                                });
                                         }
                                     }
                                 ),
                            DateTimePicker::make('reg_end')
                                 ->label('Registration End')
                                 ->translateLabel()
                                 ->seconds(false),
                            TextInput::make('max_users')
                                 ->label('Max. Attendees')
                                 ->translateLabel()
                                 ->type('number')
                                 ->minValue(1),
                            Radio::make("self_register")
                                ->boolean()
                                ->inline()
                                ->label(__("Self Registration")),
                            Radio::make("group_registration")
                                ->boolean()
                                ->inline()
                                ->label(__("Group Registration"))
                        ])
                        ->action(function(array $data, Collection $records) {
                            $records->each->update(collect($data)->filter(fn($d) => $d != null)->toArray());
                        }),
                ]),
            ]);
    }

    public function getTimeslotForm(): array {
        $previousSlot   = $this->entries->count() > 0 ? $this?->entry?->sigTimeslots?->last() : null;
        return [
            Select::make("timetable_entry_id")
                ->label(__("Timetable Entry"))
                ->translateLabel()
                ->columnSpanFull()
                ->selectablePlaceholder(false)
                ->model(TimetableEntry::class)
                ->options(function() {
                    $this->entries->load("sigEvent");
                    return $this->entries->keyBy("id")->map(
                        fn($t) =>
                            $t->start->translatedFormat("l d.m.Y, H:i") . " - " .
                            $t->end->translatedFormat("H:i") . " | " . $t->sigEvent->name_localized
                    );
                })
                ->afterStateHydrated(fn($state) => $this->entry = TimetableEntry::find($state))
                ->afterStateUpdated(fn($state) => $this->entry = TimetableEntry::find($state))
                ->default(function() {
                    return $this->entry?->id;
                })
                ->required()
                ->disabled($this->ownerRecord instanceof TimetableEntry),
            DateTimePicker::make('slot_start')
                ->label('Slot Start')
                ->translateLabel()
                ->seconds(false)
                ->live()
                ->hintAction(
                    function() {
                        if($this->entry) {
                            return Action::make("setToSigStart")
                                 ->label("Set to SIG Start")
                                 ->translateLabel()
                                 ->action(function (Set $set) {
                                     $set('slot_start', $this->entry->start->format("Y-m-d\TH:i"));
                                 });
                        }
                    }
                )
                ->afterOrEqual(fn(Get $get) => TimetableEntry::find($get('timetable_entry_id'))->start)
                ->beforeOrEqual(fn(Get $get) => TimetableEntry::find($get('timetable_entry_id'))->end)
                ->default(function() use ($previousSlot) {
                    if($previousSlot) {
                        return $previousSlot->slot_end;
                    }
                    return $this->entry?->start;
                })
                ->afterStateUpdated(function(?string $state, Set $set, Get $get) {
                    if(!$state)
                        return;

                    if(Carbon::parse($state)->toDateString() != Carbon::parse($get('slot_end'))->toDateString()) {
                        $set('slot_end', Carbon::parse($state)->addMinutes(15)->format("Y-m-d\TH:i"));
                    }
                })
                ->lazy()
                ->required(),
            DateTimePicker::make('slot_end')
                ->label('Slot End')
                ->translateLabel()
                ->seconds(false)
                ->hintAction(function() {
                    if($this->entry) {
                        return Action::make("setToSigEnd")
                             ->label("Set to SIG End")
                             ->translateLabel()
                             ->action(function (Set $set) {
                                 $set('slot_end', $this->entry->end->format("Y-m-d\TH:i"));
                             });
                        }
                    }
                )
                ->afterOrEqual('slot_start')
                ->beforeOrEqual(fn(Get $get) => TimetableEntry::find($get('timetable_entry_id'))->end)
                ->live()
                ->default(function(Get $get) use ($previousSlot) {
                    if($previousSlot) {
                        $lengthMin = $previousSlot->slot_start->diffInMinutes($previousSlot->slot_end);
                        return $previousSlot->slot_end->addMinutes($lengthMin);
                    }
                    return Carbon::parse($get('slot_start'))->addMinutes(15);
                })
                ->required(),
            DateTimePicker::make('reg_start')
                ->label('Registration Start')
                ->translateLabel()
                ->seconds(false)
                ->hintAction(
                    function() {
                        if($this->entry) {
                            return Action::make("setToConStart")
                                 ->label("Set to Con Start")
                                 ->translateLabel()
                                 ->action(function (Set $set) {
                                     $set('reg_start', app(AppSettings::class)->event_start->format("Y-m-d\TH:i"));
                                 });
                        }
                    }
                )
                ->default($previousSlot?->reg_start ?? app(AppSettings::class)->event_start),
            DateTimePicker::make('reg_end')
                ->label('Registration End')
                ->translateLabel()
                ->seconds(false)
                ->hintAction(
                    function() {
                        if($this->entry) {
                            return Action::make("setToSigStart")
                                 ->label("Set to SIG Start")
                                 ->translateLabel()
                                 ->action(function (Set $set) {
                                     $set('reg_end', $this->entry->start->format("Y-m-d\TH:i"));
                                 });
                        }
                    }
                )
                ->default($previousSlot?->reg_end ?? app(AppSettings::class)->event_end),
            TextInput::make('max_users')
                ->label('Max. Attendees')
                ->translateLabel()
                ->type('number')
                ->minValue(1)
                ->default($previousSlot?->max_users ?? 1),
            Grid::make()
                ->columns(2)
                ->columnSpanFull()
                ->schema([
                    Checkbox::make('self_register')
                        ->inline(false)
                        ->default($previousSlot?->self_register ?? true),
                    Checkbox::make('group_registration')
                        ->label(__("Group Registration"))
                        ->helperText(__("Allow the first registered attendee to manage (Add/Remove) attendees for his timeslot"))
                        ->inline(false)
                        ->default($previousSlot?->self_register ?? true),
                ])
        ];
    }
}
