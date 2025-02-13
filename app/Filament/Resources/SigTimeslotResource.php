<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\SigManagement;
use App\Filament\Resources\SigTimeslotResource\Pages;
use App\Filament\Resources\SigTimeslotResource\RelationManagers;
use App\Filament\Traits\HasActiveIcon;
use App\Models\SigTimeslot;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class SigTimeslotResource extends Resource
{
    use HasActiveIcon;
    protected static ?string $model = SigTimeslot::class;
    protected static ?string $cluster = SigManagement::class;
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?int $navigationSort = 10;
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getModelLabel(): string {
        return __("Time Slot");
    }

    public static function getPluralModelLabel(): string {
        return __("Time Slots");
    }

    public static function form(Form $form): Form {
        return $form
            ->schema([
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make("reg_possible")
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
                Tables\Columns\TextColumn::make('slot_start')
                    ->dateTime("H:i")
                    ->label("Slot Start")
                    ->translateLabel()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('slot_end')
                    ->dateTime("H:i")
                    ->label("Slot End")
                    ->translateLabel()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('sig_attendees_count')
                    ->counts("sigAttendees")
                    ->formatStateUsing(fn(string $state, Model $record) => $state . " / " . $record->max_users)
                    ->label("Attendee Count")
                    ->translateLabel()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make("sigAttendees.user.name")
                    ->listWithLineBreaks()
                    ->limitList()
                    ->action(
                        Tables\Actions\ViewAction::make("attendee_list")
                            ->recordTitle(__("Attendees"))
                            ->infolist([
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
                Tables\Columns\TextColumn::make('reg_start')
                    ->label("Registration Start")
                    ->translateLabel()
                    ->dateTime()
                    ->badge()
                    ->toggleable()
                    ->color(fn($record) => $record->reg_start->isBefore(now()) ? Color::Green : Color::Neutral)
                    ->sortable(),
                Tables\Columns\TextColumn::make('reg_end')
                    ->label("Registration End")
                    ->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable()
                    ->color(fn($record) => $record->reg_end->isAfter(now()) ? Color::Green : Color::Red)
                    ->badge(),
                Tables\Columns\TextColumn::make("notes")
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->listWithLineBreaks()
                    ->limit(100)
                    ->label("Notes")
                    ->translateLabel(),
            ])
            ->defaultGroup(
                Tables\Grouping\Group::make("timetableEntry.sigEvent.id")
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
                Tables\Filters\SelectFilter::make("sigEvent")
                    ->relationship("timetableEntry.sigEvent", "name", fn(Builder $query) => $query->whereHas("sigTimeslots"))
                    ->searchable()
                    ->preload()
                    ->label("Event")
                    ->translateLabel(),
            ])
            ->defaultSort("slot_start")
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            RelationManagers\SigAttendeeRelationManager::class,
        ];
    }

    public static function getPages(): array {
        return [
            'index' => Pages\ListSigTimeslots::route('/'),
            'create' => Pages\CreateSigTimeslot::route('/create'),
            'view' => Pages\ViewSigTimeslot::route('/{record}'),
            'edit' => Pages\EditSigTimeslot::route('/{record}/edit'),
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
