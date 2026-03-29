<?php

namespace App\Filament\Resources\SigTimeslots;

use BackedEnum;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ViewAction;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\SigTimeslots\RelationManagers\SigAttendeeRelationManager;
use App\Filament\Resources\SigTimeslots\Pages\ListSigTimeslots;
use App\Filament\Resources\SigTimeslots\Pages\CreateSigTimeslot;
use App\Filament\Resources\SigTimeslots\Pages\ViewSigTimeslot;
use App\Filament\Resources\SigTimeslots\Pages\EditSigTimeslot;
use App\Filament\Clusters\SigManagement\SigManagementCluster;
use App\Filament\Traits\HasActiveIcon;
use App\Models\SigTimeslot;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Support\Colors\Color;
use Filament\Tables\Table;
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
            ->components([
                Textarea::make('description')
                    ->columnSpanFull(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
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
                    ->color(fn($record) => $record->reg_end->isAfter(now()) ? Color::Green : Color::Red)
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
            'create' => CreateSigTimeslot::route('/create'),
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
}
