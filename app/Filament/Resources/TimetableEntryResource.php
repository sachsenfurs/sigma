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

class TimetableEntryResource extends Resource
{
    protected static ?string $model = TimetableEntry::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = SigPlanning::class;
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getNavigationGroup(): ?string
    {
        return __('Event Schedule');
    }

    public static function getLabel(): ?string
    {
        return __('Event Schedule');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Event Schedule');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                self::getSigEventField(),
                self::getSigLocationField(),
                self::getSigStartField(),
                self::getSigEndField(),
                self::getSigNewField(),
                self::getSigCancelledField(),
                self::getSigHideField(),
                self::getResetUpdateField(),
                self::getSendUpdateField(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::getTableColumns())
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTimetableEntries::route('/'),
            'create' => Pages\CreateTimetableEntry::route('/create'),
            'edit' => Pages\EditTimetableEntry::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            TimeslotTable::class,
        ];
    }

    private static function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('timestamp')
                ->getStateUsing(function ($record) {
                    $suffix = '';
                    if ($record->cancelled) {
                        $suffix = ' - ' . __('Cancelled');
                    } else {
                        if ($record->new) {
                            $suffix = ' - ' . __('New');
                        }
                        if ($record->hasTimeChanged) {
                            $suffix = ' - ' . __('Changed');
                        }
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
                ->translateLabel(),
            Tables\Columns\TextColumn::make('sigEvent.name')
                ->label('Event')
                ->translateLabel()
                ->searchable(),
            Tables\Columns\TextColumn ::make('sigLocation.name')
                ->badge()
                ->label('Location')
                ->translateLabel(),
        ];
    }

    private static function getLocationFilter(): Tables\Filters\SelectFilter
    {
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

    private static function getSigEventField(): Forms\Components\Component
    {
        return Forms\Components\Select::make('sig_event_id')
            ->label('Event')
            ->translateLabel()
            ->relationship('sigEvent', 'name', fn (Builder $query) => $query->orderBy('name'))
            ->getOptionLabelFromRecordUsing(function (Model $record) {
                return $record->name . ' - ' . $record->sigLocation->name . ' ' . $record->sigLocation->description;
            })
            ->required()
            ->disabled(fn (string $operation): bool => $operation !== 'create');
    }

    private static function getSigLocationField(): Forms\Components\Component
    {
        return Forms\Components\Select::make('sig_location_id')
            ->label('Different Location')
            ->translateLabel()
            ->options(function(): array {
                return SigLocation::orderBy('name')->get()->mapWithKeys(function ($sigLocation) {
                    if ($sigLocation->description) {
                        return [$sigLocation->id => $sigLocation->name . ' - ' . $sigLocation->description];
                    }
                    return [$sigLocation->id => $sigLocation->name];
                })->toArray();
            })
            ->searchable()
            ->preload()
            ->placeholder(__('No different Location'));
    }

    private static function getSigStartField(): Forms\Components\Component
    {
        return Forms\Components\DateTimePicker::make('start')
            ->label('Beginning')
            ->translateLabel()
            ->format('Y-m-d\TH:i')
            ->seconds(false)
            ->required();
    }

    private static function getSigEndField(): Forms\Components\Component
    {
        return Forms\Components\DateTimePicker::make('end')
            ->label('End')
            ->translateLabel()
            ->format('Y-m-d\TH:i')
            ->seconds(false)
            ->required();
    }

    private static function getSigNewField(): Forms\Components\Component
    {
        return Forms\Components\Checkbox::make('new')
            ->label('New Event')
            ->translateLabel()
            ->hidden(fn (string $operation): bool => $operation !== 'edit');
    }

    private static function getSigCancelledField(): Forms\Components\Component
    {
        return Forms\Components\Checkbox::make('cancelled')
            ->label('Event Cancelled')
            ->translateLabel()
            ->hidden(fn (string $operation): bool => $operation !== 'edit');
    }

    private static function getSigHideField(): Forms\Components\Component
    {
        return Forms\Components\Checkbox::make('hide')
            ->label('Internal Event')
            ->translateLabel();
    }

    private static function getResetUpdateField(): Forms\Components\Component
    {
        return Forms\Components\Checkbox::make('reset_update')
            ->label('Reset \'Changed\'-flag')
            ->translateLabel()
            ->hidden(fn (string $operation): bool => $operation !== 'edit');
    }

    private static function getSendUpdateField(): Forms\Components\Component
    {
        return Forms\Components\Checkbox::make('send_update')
            ->label('Announce Changes')
            ->translateLabel()
            ->helperText(__('This needs to be checked if the event should be marked as changed!'))
            ->hidden(fn (string $operation): bool => $operation !== 'edit');
    }
}
