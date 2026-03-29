<?php

namespace App\Filament\Resources;

use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Fieldset;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\TextSize;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use App\Filament\Resources\TimetableEntryResource\Pages\EditTimetableEntry;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\TimetableEntryResource\Pages\ListTimetableEntries;
use App\Filament\Resources\TimetableEntryResource\Pages\CreateTimetableEntry;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Schemas\Components\Component;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Textarea;
use App\Filament\Clusters\SigPlanning;
use App\Filament\Helper\FormHelper;
use App\Filament\Resources\TimetableEntryResource\Pages;
use App\Filament\Traits\HasActiveIcon;
use App\Models\DepartmentInfo;
use App\Models\SigFavorite;
use App\Models\SigLocation;
use App\Models\TimetableEntry;
use App\Settings\AppSettings;
use Filament\Forms;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\HtmlString;
use Nette\Utils\Html;

class TimetableEntryResource extends Resource
{
    use HasActiveIcon;
    protected static ?string $model = TimetableEntry::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $cluster = SigPlanning::class;
    protected static ?int $navigationSort = 10;

    protected static ?\Filament\Pages\Enums\SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getLabel(): ?string {
        return __('Event Schedule');
    }

    public static function getPluralLabel(): ?string {
        return __('Event Schedule');
    }

    public static function form(Schema $schema): Schema {
        return $schema
            ->components(self::getSchema());
    }

    public static function getSchema(): array {
        return [
            self::getSigEventField(),
            self::getSigLocationField(),
            self::getSigStartField(),
            self::getSigEndField(),
            Fieldset::make("Event Settings")
                ->columnSpanFull()
                ->schema([
                    self::getSigNewField(),
                    self::getSigHideField(),
                    self::getSigCancelledField(),
                ])
                ->columns(3),
            Fieldset::make("Communication Settings")
                ->translateLabel()
                ->columnSpanFull()
                ->schema([
                    self::getSendUpdateField(),
                ])
                ->hidden(fn(string $operation): bool => $operation == "create"),
            self::getResetUpdateField(),
            self::getSigApprovalField(),
            self::getCommentField()
                ->columnSpanFull(),

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
            ->recordActions([
                ViewAction::make("requirements")
                    ->schema([
                        TextEntry::make("sigEvent.name_localized")
                            ->inlineLabel()
                            ->label(__("Event Name"))
                            ->color('primary')
                            ->url(fn($record) => SigEventResource::getUrl('view', ['record' => $record->sigEvent])),
                        TextEntry::make("sigEvent.description_localized")
                            ->label("Description")
                            ->markdown()
                            ->listWithLineBreaks()
                            ->limit(500)
                            ->size(TextSize::ExtraSmall)
                            ->color('gray')
                            ->formatStateUsing(fn($state) => new HtmlString(nl2br(e($state))))
                            ->translateLabel(),
                        TextEntry::make("sigEvent.additional_info")
                            ->label("Additional Information")
                            ->translateLabel()
                            ->listWithLineBreaks()
                            ->formatStateUsing(fn($state) => new HtmlString(nl2br(e($state)))),
                        RepeatableEntry::make("sigEvent.departmentInfos")
                            ->visible(fn($state) => $state)
                            ->label("SIG Requirements")
                            ->translateLabel()
                            ->schema([
                                TextEntry::make("userRole.name_localized")
                                    ->label("")
                                    ->prefixAction(
                                        Action::make("edit")
                                            ->label(__("Edit"))
                                            ->authorize("create", DepartmentInfo::class)
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
                EditAction::make()
                    ->using(EditTimetableEntry::getEditAction())
                    ->url(null),
            ])
            ->recordUrl(null)
            ->recordAction("requirements")
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->recordClasses(fn(TimetableEntry $record) => $record->end->isPast() ? "bg-gray-800 text-gray-500" : "");
    }

    public static function getRelations(): array {
        return [
            //
        ];
    }

    public static function getPages(): array {
        return [
            'index' => ListTimetableEntries::route('/'),
            'create' => CreateTimetableEntry::route('/create'),
            'edit' => EditTimetableEntry::route('/{record}/edit'),
        ];
    }

    public static function getTableColumns(): array {
        return [
            TextColumn::make('timestamp')
                ->getStateUsing(function ($record, $table) {
                    $suffix = '';
                    $prefix = '';
                    if ($record->cancelled) {
                        $suffix = ' - ' . __('Cancelled');
                    } else {
                        if ($record->new)
                            $suffix = ' - ' . __('New');
                        if ($record->has_time_changed)
                            $suffix = ' ' . __('Changed');
                    }
                    if(!empty($table->getSortColumn()))
                        $prefix = $record->start->translatedFormat("D") . " - ";

                    return $prefix . $record->start->format('H:i') . ' - ' . $record->end->format('H:i') . $suffix;
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
            TextColumn::make('sigEvent.name_localized')
                ->label('Event')
                ->translateLabel()
                ->color(fn(Model $record) => $record->hide ? Color::Gray : "default")
                ->badge(fn(Model $record) => $record->hide)
                ->searchable(['name', 'name_en']),
            TextColumn::make('sigEvent.sigHosts.name')
                 ->color("default")
                 ->label('Host')
                 ->translateLabel()
                 ->searchable(),
            TextColumn ::make('sigLocation.name')
                ->badge()
                ->label('Location')
                ->translateLabel(),
            TextColumn::make("sig_timeslots_count")
                ->label("Timeslot Count")
                ->translateLabel()
                ->counts("sigTimeslots")
                ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make("favorites_count")
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
                    ViewAction::make()
                        ->schema([
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

    private static function getLocationFilter(): SelectFilter {
        return SelectFilter::make('sigLocation')
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

    public static function getSigEventField(): Component {
        return Select::make('sig_event_id')
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
                        return Action::make("edit")
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

    public static function getSigLocationField(): Component {
        return Select::make('sig_location_id')
            ->label('Location')
            ->translateLabel()
            ->relationship('sigLocation', 'name')
            ->model(TimetableEntry::class)
            ->options(function(): array {
                return SigLocation::all()->sortBy("name_localized")->mapWithKeys(function ($sigLocation) {
                    $name = $sigLocation->description_localized ? $sigLocation->name_localized . " - " . $sigLocation->description_localized : $sigLocation->name_localized;
                    return [ $sigLocation->id => $name ];
                })->toArray();
            })
            ->getOptionLabelFromRecordUsing(FormHelper::formatLocationWithDescription()) // formatting search results
            ->required()
            ->searchable(['name', 'name_en', 'description', 'description_en'])
            ->preload();
    }

    public static function getSigStartField(): Component {
        return DateTimePicker::make('start')
            ->label('Beginning')
            ->translateLabel()
            ->format('Y-m-d\TH:i')
            ->seconds(false)
            ->required();
    }

    public static function getSigEndField(): Component {
        return DateTimePicker::make('end')
            ->label('End')
            ->translateLabel()
            ->format('Y-m-d\TH:i')
            ->seconds(false)
            ->columns(1)
            ->afterOrEqual('start')
            ->required();
    }

    public static function getSigNewField(): Component {
        return Toggle::make('new')
            ->label('New Event')
            ->translateLabel()
            ->formatStateUsing(function(?Model $record) {
                // automatically prefill to "true" when con (in this case the first event) has started
                if(!$record) // only prefill when a new record is created (current $record == null)
                    return Carbon::now()->isAfter(app(AppSettings::class)->event_start);
                return (bool)$record->new;
            });
    }

    public static function getSigCancelledField(): Component {
        return Toggle::make('cancelled')
            ->label('Event Cancelled')
            ->onColor("danger")
            ->visible(fn(?Model $record): bool => $record !== null AND ($record instanceof TimetableEntry))
            ->translateLabel();
    }

    public static function getSigHideField(): Component {
        return Toggle::make('hide')
            ->label('Internal Event')
            ->translateLabel();
    }

    public static function getResetUpdateField(): Component {
        return Checkbox::make('reset_update')
            ->label('Reset \'Changed\'-flag')
            ->translateLabel()
            ->visible(fn (string $operation, ?Model $record): bool => ($record?->hasEventChanged() ?? false));
    }

    public static function getSendUpdateField(): Component {
        return Checkbox::make('send_update')
            ->label('Announce Changes')
            ->translateLabel()
            ->formatStateUsing(fn() => app(AppSettings::class)->show_schedule_date->isBefore(now()))
            ->helperText(__('This needs to be checked if the event should be marked as changed!'));
    }

    private static function getDepartmentFilter(): SelectFilter {
        return SelectFilter::make("department")
              ->label("SIG Requirements")
              ->translateLabel()
              ->searchable()
              ->preload()
              ->relationship("sigEvent.departmentInfos.userRole", "name");
    }

    private static function getSigApprovalField(): Checkbox {
        return Checkbox::make("approval")
            ->formatStateUsing(fn($state) => !$state)
            ->mutateDehydratedStateUsing(fn($state) => !$state)
            ->label(__("WIP"))
            ->helperText(__("Work in Progress, times may still change!"));
    }

    private static function getCommentField() {
        return Textarea::make("comment")
            ->label("Comment")
            ->translateLabel();
    }
}
