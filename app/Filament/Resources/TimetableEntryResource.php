<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\SigPlanning;
use App\Filament\Resources\TimetableEntryResource\Pages;
use App\Filament\Traits\HasActiveIcon;
use App\Models\SigFavorite;
use App\Models\SigLocation;
use App\Models\TimetableEntry;
use App\Settings\AppSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\HtmlString;

class TimetableEntryResource extends Resource
{
    use HasActiveIcon;
    protected static ?string $model = TimetableEntry::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $cluster = SigPlanning::class;
    protected static ?int $navigationSort = 10;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

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
                ->translateLabel()
                ->schema([
                    self::getSendUpdateField(),
                ])
                ->hidden(fn(string $operation): bool => $operation == "create"),
            self::getResetUpdateField(),
        ];
    }

    public static function table(Table $table): Table {
        return $table
            ->modifyQueryUsing(fn($query) => $query->with(["sigEvent.sigHosts.user"]))
            ->columns(static::getTableColumns())
            ->defaultPaginationPageOption('all')
            ->defaultGroup(
                $table->getSortColumn()
                    ? null
                    : Group::make('start')
                     ->label(__("Day"))
                     ->collapsible()
                     ->date()
                     ->titlePrefixedWithLabel(false)
            )
            ->filters([
                self::getLocationFilter(),
                self::getDepartmentFilter(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make("requirements")
                    ->infolist([
                        TextEntry::make("sigEvent.description_localized")
                            ->label("Description")
                            ->translateLabel()
                            ->inlineLabel(),
                        TextEntry::make("sigEvent.additional_info")
                            ->label("Additional Information")
                            ->translateLabel()
                            ->listWithLineBreaks()
                            ->formatStateUsing(fn($state) => new HtmlString(nl2br(e($state)))),
                        RepeatableEntry::make("sigEvent.departmentInfos")
                            ->label("SIG Requirements")
                            ->translateLabel()
                            ->schema([
                                TextEntry::make("userRole.name_localized")
                                    ->label("")
                                    ->prefixAction(
                                        Action::make("edit")
                                            ->url(function(Model $record) {
                                                return DepartmentInfoResource::getUrl("edit", ['record' => $record]);
                                            })
                                            ->icon("heroicon-o-pencil-square")
                                    ),
                                TextEntry::make("additional_info")
                                    ->label("")
                                    ->listWithLineBreaks()
                                    ->formatStateUsing(fn($state) => new HtmlString(nl2br(e($state))))
                            ])
                    ])
                    ->modal(),
                Tables\Actions\EditAction::make()
                    ->using(Pages\EditTimetableEntry::getEditAction())
                    ->url(null),
            ])
            ->recordUrl(null)
            ->recordAction("requirements")
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
                        if ($record->has_time_changed)
                            $suffix = ' - ' . __('Changed');
                    }
                    return $record->start->format('H:i') . ' - ' . $record->end->format('H:i') . $suffix;
                })
                ->badge(function (Model $record) {
                    return $record->cancelled || $record->new || $record->has_time_changed;
                })
                ->color(function (Model $record) {
                    if ($record->cancelled) {
                        return 'danger';
                    } else if ($record->new || $record->has_time_changed) {
                        return 'info';
                    }
                    return 'secondary';
                })
                ->label('Time span')
                ->width(10)
                ->translateLabel(),
            Tables\Columns\TextColumn::make('sigEvent.name_localized')
                ->label('Event')
                ->translateLabel()
                ->color(fn(Model $record) => $record->hide ? Color::Gray : null)
                ->badge(fn(Model $record) => $record->hide)
                ->searchable(['name', 'name_en']),
            Tables\Columns\TextColumn::make('sigEvent.sigHosts.name')
                 ->label('Host')
                 ->translateLabel()
                 ->searchable(),
            Tables\Columns\TextColumn ::make('sigLocation.name')
                ->badge()
                ->label('Location')
                ->translateLabel(),
            Tables\Columns\TextColumn::make("sig_timeslots_count")
                ->label("Timeslot Count")
                ->translateLabel()
                ->counts("sigTimeslots")
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make("favorites_count")
                ->label("Favorites")
                ->translateLabel()
                ->counts("favorites")
                ->icon("heroicon-s-heart")
                ->badge()
                ->color(function($state) {
                    $max = SigFavorite::getMaxLikes();
                    if($state > 0.95 * $max)
                        return Color::Pink;
                    if($state > 0.75 * $max)
                        return Color::Fuchsia;
                    if($state > 0.5 * $max)
                        return Color::Purple;
                    if($state > 0.3 * $max)
                        return Color::Indigo;
                    return Color::Gray;
                })
                ->toggleable(isToggledHiddenByDefault: true)
                ->sortable()
                ->action(
                    Tables\Actions\ViewAction::make()
                        ->infolist([
                            RepeatableEntry::make("favorites")
                                ->schema([
                                    TextEntry::make("user")
                                        ->label("")
                                        ->formatStateUsing(fn($state) => $state->name . " (#" . $state->reg_id . ")"),
                                ])
                        ]),

                ),
        ];
    }

    private static function getLocationFilter(): Tables\Filters\SelectFilter {
        return Tables\Filters\SelectFilter::make('sigLocation')
            ->label('Location')
            ->translateLabel()
            ->relationship('sigLocation', 'name', fn (Builder $query) => $query->orderBy('name'))
            ->options(function (Model $record) {
                // If the location has a description, append it to the name
                if ($record->description_localized) {
                    return $record->name_localized . ' - ' . $record->description_localized;
                }
                return $record->name;
            })
            ->getOptionLabelFromRecordUsing(function (Model $record) {
                // If the location has a description, append it to the name
                if ($record->description_localized) {
                    return $record->name_localized . ' - ' . $record->description_localized;
                }
                return $record->name_localized;
            })
            ->searchable(['name', 'name_en'])
            ->preload();
    }

    public static function getSigEventField(): Forms\Components\Component {
        return Forms\Components\Select::make('sig_event_id')
            ->label('Event')
            ->translateLabel()
            ->createOptionForm(fn($form) => SigEventResource::form($form))
            ->autofocus(fn($state) => $state == null)
            ->model(TimetableEntry::class)
            ->relationship('sigEvent', 'name', fn (Builder $query) => $query->orderBy('name')->with('sigHosts'))
            ->getOptionLabelFromRecordUsing(function (Model $record) {
                return $record->name_localized . ' - ' . $record->sigHosts->pluck("name")->join(", ");
            })
            ->live()
            ->hintAction(
                function($state) {
                    if(filled($state)) {
                        return Forms\Components\Actions\Action::make("edit")
                            ->label("Edit")
                            ->translateLabel()
                            ->url(SigEventResource::getUrl("edit", ['record' => $state]));
                    }
                }
            )
            ->required()
            ->searchable(['name', 'name_en'])
            ->preload();
    }

    public static function getSigLocationField(): Forms\Components\Component {
        return Forms\Components\Select::make('sig_location_id')
            ->label('Location')
            ->translateLabel()
            ->relationship('sigLocation', 'name_localized')
            ->model(TimetableEntry::class)
            ->options(function(): array {
                return SigLocation::all()->sortBy("name_localized")->mapWithKeys(function ($sigLocation) {
                    $name = $sigLocation->description_localized ? $sigLocation->name_localized . " - " . $sigLocation->description_localized : $sigLocation->name_localized;
                    return [ $sigLocation->id => $name ];
                })->toArray();
            })
            ->required()
            ->searchable(['name', 'name_en'])
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
            ->afterOrEqual('start')
            ->required();
    }

    public static function getSigNewField(): Forms\Components\Component {
        return Forms\Components\Toggle::make('new')
            ->label('New Event')
            ->translateLabel()
            ->formatStateUsing(function(?Model $record) {
                // automatically prefill to "true" when con (in this case the first event) has started
                if(!$record) // only prefill when a new record is created (current $record == null)
                    return Carbon::now()->isAfter(app(AppSettings::class)->event_start);
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
            ->label('Reset \'Changed\'-flag')
            ->translateLabel()
            ->visible(fn (string $operation, ?Model $record): bool => ($record?->hasEventChanged() ?? false));
    }

    public static function getSendUpdateField(): Forms\Components\Component {
        return Forms\Components\Checkbox::make('send_update')
            ->label('Announce Changes')
            ->translateLabel()
            ->formatStateUsing(fn() => app(AppSettings::class)->show_schedule_date->isBefore(now()))
            ->helperText(__('This needs to be checked if the event should be marked as changed!'));
    }

    private static function getDepartmentFilter(): Tables\Filters\SelectFilter {
        return Tables\Filters\SelectFilter::make("department")
              ->label("SIG Requirements")
              ->translateLabel()
              ->searchable()
              ->preload()
              ->relationship("sigEvent.departmentInfos.userRole", "name");
    }
}
