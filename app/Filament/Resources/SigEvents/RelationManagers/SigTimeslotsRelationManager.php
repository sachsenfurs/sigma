<?php

namespace App\Filament\Resources\SigEvents\RelationManagers;

use BackedEnum;
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
use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Filament\Resources\SigTimeslots\SigTimeslotResource;
use App\Models\SigEvent;
use App\Models\SigTimeslot;
use App\Models\TimetableEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class SigTimeslotsRelationManager extends RelationManager
{
    public ?Collection $entries = null;

    protected static string $relationship = 'sigTimeslots';
    protected static string | BackedEnum | null $icon = 'heroicon-o-clock';

    public static function getTitle(Model $ownerRecord, string $pageClass): string {
        return __("Time Slots");
    }

    public function mount(): void {
        parent::mount();
        if($this->ownerRecord instanceof SigEvent) {
            $this->entries = $this->ownerRecord->timetableEntries;
        }
        if($this->ownerRecord instanceof TimetableEntry) {
            $this->entries = $this->ownerRecord->sigEvent->timetableEntries;
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
            ->components(SigTimeslotResource::getFormComponents());
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
                    ->disabled(($this->entries?->count() ?? 0) == 0)
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
                        ->schema(SigTimeslotResource::getFormComponents())
                        ->action(function(array $data, Collection $records) {
                            $records->each->update(collect($data)->filter(fn($d) => $d != null)->toArray());
                        }),
                ]),
            ]);
    }

}
