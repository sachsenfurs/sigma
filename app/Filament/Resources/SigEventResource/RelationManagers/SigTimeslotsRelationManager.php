<?php

namespace App\Filament\Resources\SigEventResource\RelationManagers;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Filament\Resources\SigTimeslotResource;
use App\Models\SigEvent;
use App\Models\SigTimeslot;
use App\Models\TimetableEntry;
use App\Settings\AppSettings;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
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
    protected static ?string $icon = 'heroicon-o-clock';

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

    public function form(Form $form): Form {
        return $form
            ->schema($this->getTimeslotForm());
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string {
        return $ownerRecord->sigTimeslots->count() ?: null;
    }

    public function table(Table $table): Table {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("timetableEntry")
                    ->dateTime()
                    ->formatStateUsing(fn($state) => $state->start->translatedFormat("H:i") . " - ". $state->end->translatedFormat("H:i"))
                    ->label(__("Timetable Entry"))
                    ->translateLabel(),
                Tables\Columns\IconColumn::make("self_register")
                    ->boolean(),
                Tables\Columns\IconColumn::make("group_registration")
                    ->label(__("Group Registration"))
                    ->boolean(),
                Tables\Columns\TextColumn::make('slot_start')
                    ->label('Slot Start')
                    ->translateLabel()
                    ->dateTime('H:i'),
                Tables\Columns\TextColumn::make('slot_end')
                    ->label('Slot End')
                    ->translateLabel()
                    ->dateTime('H:i'),
                Tables\Columns\TextColumn::make('reg_start')
                    ->label('Registration Start')
                    ->translateLabel()
                    ->dateTime(),
                Tables\Columns\TextColumn::make('reg_end')
                    ->label('Registration End')
                    ->translateLabel()
                    ->dateTime(),
                Tables\Columns\TextColumn::make('max_users')
                    ->label('Attendees')
                    ->translateLabel()
                    ->formatStateUsing(function (SigTimeslot $timeslot) {
                        return $timeslot->sigAttendees->count() . '/' . $timeslot->max_users;
                    })
                    ->tooltip(fn($record) => $record->sigAttendees->pluck("user.name")->join(", ")),
            ])
            ->recordUrl(fn($record) => SigTimeslotResource::getUrl("view", ['record' => $record]))
            ->defaultGroup(
                Tables\Grouping\Group::make("timetableEntry.start")
                    ->collapsible()
                    ->label('')
                    ->date(),
            )
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->disabled($this->entries->count() == 0)
            ])
            ->actions([
                ViewAction::make('view')
                    ->label('Attendees')
                    ->translateLabel()
                    ->icon('heroicon-s-users')
                    ->modalWidth(MaxWidth::Medium)
                    ->modalHeading(__('Attendee List'))
                    ->infolist([
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->label(""),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make("edit")
                        ->label("Edit")
                        ->translateLabel()
                        ->icon("heroicon-o-pencil-square")
                        ->form([
                            DateTimePicker::make('reg_start')
                                 ->label('Registration Start')
                                 ->translateLabel()
                                 ->seconds(false),
                            DateTimePicker::make('reg_end')
                                 ->label('Registration End')
                                 ->translateLabel()
                                 ->seconds(false),
                            TextInput::make('max_users')
                                 ->label('Max. Attendees')
                                 ->translateLabel()
                                 ->type('number')
                                 ->minValue(1),
                            Checkbox::make("self_register")
                                ->label(__("Self Registration")),
                            Checkbox::make("group_registration")
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
