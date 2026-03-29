<?php

namespace App\Filament\Resources\DepartmentInfos;

use App\Filament\Resources\SigEvents\SigEventResource;
use BackedEnum;
use Filament\Schemas\Schema;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\DepartmentInfos\Pages\ListDepartmentInfos;
use App\Filament\Resources\DepartmentInfos\Pages\EditDepartmentInfo;
use Filament\Tables\Columns\TextColumn;
use Filament\Schemas\Components\Component;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\Textarea;
use App\Filament\Traits\HasActiveIcon;
use App\Models\DepartmentInfo;
use App\Models\SigEvent;
use App\Models\UserRole;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\Rules\Unique;
use UnitEnum;

class DepartmentInfoResource extends Resource
{
    use HasActiveIcon;
    protected static ?string $model = DepartmentInfo::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-archive-box';

    protected static string | UnitEnum | null $navigationGroup = 'SIG';

    protected static ?int $navigationSort = 20;

    public static function getLabel(): ?string {
        return __('SIG Requirement');
    }

    public static function getPluralLabel(): ?string {
        return __('SIG Requirements');
    }

    public static function form(Schema $schema): Schema {
        return $schema
            ->components([
                self::getSigInfoFiled(),
                self::getSIGField(),
                self::getUserRoleField(),
                self::getAdditionalInfoField(),
            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->modifyQueryUsing(fn(\Illuminate\Database\Eloquent\Builder $query) =>
                $query->with(["sigEvent", "sigEvent.departmentInfos", "userRole", "sigEvent.timetableEntries.sigLocation"])
                    ->whereHas("sigEvent.departmentInfos", fn(\Illuminate\Database\Eloquent\Builder $query) =>
                        $query->where("additional_info", "!=", "")
                    )
                    ->select("department_infos.*", "timetable_entries.start", "timetable_entries.end", "timetable_entries.id AS tid")
                    ->rightJoinRelationship("sigEvent.timetableEntries")
                    ->orderBy("start")
            )
            ->columns(self::getTableColumns())
            ->defaultGroup(
                Group::make('start')
                    ->label('')
                    ->collapsible()
                    ->getTitleFromRecordUsing(function ($record) {
                        return Carbon::parse($record->start)->translatedFormat("l, d.m.Y - H:i")
                        . " | " . $record->sigEvent->name_localized
                        . " | " . $record->sigEvent->timetableEntries->find($record->tid)?->sigLocation->name_localized ?? "";
                    })
            )
            ->recordActions([
                ViewAction::make()
                    ->label(false)
                    ->icon(false)
                    ->modalHeading(__('Requirements to Department'))
                    ->schema([
                        TextEntry::make('sigEvent.name')
                            ->label('SIG')
                            ->inlineLabel(),
                        TextEntry::make('userRole')
                            ->label('Department')
                            ->formatStateUsing(fn($state) => $state->name_localized)
                            ->badge()
                            ->translateLabel()
                            ->inlineLabel(),
                        TextEntry::make('additional_info')
                            ->label('Requirements to Department')
                            ->listWithLineBreaks()
                            ->formatStateUsing(fn($state) => new HtmlString(nl2br(e($state))))
                            ->translateLabel(),
                        RepeatableEntry::make("sigEvent.timetableEntries")
                            ->label("Events")
                            ->translateLabel()
                            ->schema([
                                TextEntry::make("start")
                                    ->label("")
                                    ->extraAttributes(fn($record) => $record->start->isPast() ? ['style' => "opacity:0.5"] : [])
                                    ->dateTime()
                                    ->formatStateUsing(function ($record) {
                                        $start = Carbon::parse($record->start);
                                        $end    = Carbon::parse($record->end);
                                        return $start->translatedFormat("l, H:i") . " - " . $end->translatedFormat("H:i") . " (" . round($end->diff($start)->minutes / 60, 2) . "h)";
                                    }),
                                TextEntry::make("sigLocation.name_localized")
                                    ->label("")
                                    ->extraAttributes(fn($record) => $record->start->isPast() ? ['style' => "opacity:0.5"] : [])
                                    ->formatStateUsing(fn(Model $record) => $record->sigLocation->name_localized . " - " . $record->sigLocation->description_localized),

                            ]),
                    ]),
                EditAction::make()
                    ->label("Edit SIG")
                    ->translateLabel()
                    ->color(Color::Gray)
                    ->url(fn(Model $record) => SigEventResource::getUrl("edit", ['record' => $record->sigEvent])),
                EditAction::make()
                    ->modal()
                    ->url(null),
                DeleteAction::make(),
            ])
            ->recordUrl(null)
            ->filtersLayout(FiltersLayout::AboveContent)
            ->filters([
                SelectFilter::make("userRole")
                    ->label("Department")
                    ->translateLabel()
                    ->searchable()
                    ->preload()
                    ->multiple()
                    ->getOptionLabelFromRecordUsing(fn($record) => $record->name_localized)
                    ->relationship("userRole", "name"),
                Filter::make("hide_past_events")
                    ->label(__("Hide past events"))
                    ->query(fn(\Illuminate\Database\Eloquent\Builder $query) => $query->where("end", ">", now()))
                    ->default(),
            ])
            ->persistFiltersInSession()
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => ListDepartmentInfos::route('/'),
            'edit' => EditDepartmentInfo::route('/{record}/edit'),
        ];
    }

    private static function getTableColumns(): array {
        return [
            TextColumn::make('userRole')
                ->badge()
                ->formatStateUsing(fn($state) => $state->name_localized)
                ->label('Department')
                ->translateLabel(),
            TextColumn::make('additional_info')
                ->formatStateUsing(fn($state) => new HtmlString(nl2br(e($state))))
                ->label('Requirements to Department')
                ->translateLabel()
                ->color(fn($record) => Carbon::parse($record->end)->isPast() ? Color::Gray : "")
                ->limit(),
        ];
    }

    private static function getSIGField(): Component {
        return
            Select::make('sig_event_id')
                ->label('Assigned SIG')
                ->translateLabel()
                ->live()
                ->options(SigEvent::all()->pluck('name_localized', 'id'))
                ->searchable()
                ->required();
    }

    private static function getUserRoleField(): Component {
        return
            Select::make('user_role_id')
                ->label('Department')
                ->translateLabel()
                ->options(UserRole::all()->pluck('name_localized', 'id'))
                ->searchable()
                ->unique(ignoreRecord: true, modifyRuleUsing: function(Unique $rule, Get $get) {
                    return $rule->where(function(Builder $query) use($get) {
                       return $query->where("sig_event_id", $get('sig_event_id'))
                           ->where("user_role_id", $get('user_role_id'));
                    });
                })
                ->required();
    }

    private static function getAdditionalInfoField(): Component {
        return
            Textarea::make('additional_info')
                ->label('Requirements to Department')
                ->translateLabel()
                ->required()
                ->rows(4)
                ->columnSpanFull();
    }

    private static function getSigInfoFiled() {
        return TextEntry::make("additional_info")
            ->columnSpanFull()
            ->state(fn(Get $get) => new HtmlString(nl2br(e(SigEvent::find($get("sig_event_id"))?->additional_info))));
    }
}
