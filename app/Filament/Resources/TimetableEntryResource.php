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
            ->schema(static::getSchema());
    }

    public static function getSchema() {
        return [
            Forms\Components\Select::make('sig_event_id')
                     ->label('Event')
                     ->translateLabel()
                     ->relationship('sigEvent', 'name', fn (Builder $query) => $query->orderBy('name')->with("sigHost"))
                     ->getOptionLabelFromRecordUsing(function (Model $record) {
                         return $record->name . ' - ' . $record->sigHost->name;
                     })
                     ->required()
                     ->searchable()
                     ->preload(),
            Forms\Components\Select::make('sig_location_id')
                     ->label('Location')
                     ->translateLabel()
                     ->relationship('sigLocation', 'name')
                     ->options(function(): array {
                         return SigLocation::orderBy('name')->get()->mapWithKeys(function ($sigLocation) {
                             $name = $sigLocation->description ? $sigLocation->name . " - " . $sigLocation->description : $sigLocation->name;
                             return [ $sigLocation->id => $name ];
                         })->toArray();
                     })
                     ->searchable()
                     ->preload(),
            Forms\Components\DateTimePicker::make('start')
                     ->label('Beginning')
                     ->translateLabel()
                     ->format('Y-m-d\TH:i')
                     ->seconds(false)
                     ->required(),
            Forms\Components\DateTimePicker::make('end')
                     ->label('End')
                     ->translateLabel()
                     ->format('Y-m-d\TH:i')
                     ->seconds(false)
                     ->required(),
            Forms\Components\Checkbox::make('new')
                     ->label('New Event')
                     ->translateLabel()
                     ->formatStateUsing(function() {
                         // automatically prefill to "true" when con (in this case the first event) has started
                         return Carbon::now()->isAfter(TimetableEntry::orderBy('start')->first()->start);
                     })
            ,
            Forms\Components\Checkbox::make('cancelled')
                     ->label('Event Cancelled')
                     ->translateLabel()
                     ->hidden(fn (string $operation): bool => $operation !== 'edit'),
            Forms\Components\Checkbox::make('hide')
                     ->label('Internal Event')
                     ->translateLabel(),
            Forms\Components\Checkbox::make('reset_update')
                     ->label('Reset \'Changed\'-flag')
                     ->translateLabel()
                     ->dehydrated(false)
                     ->hidden(fn (string $operation): bool => $operation !== 'edit'),
            Forms\Components\Checkbox::make('send_update')
                     ->label('Announce Changes')
                     ->translateLabel()
                     ->dehydrated(false)
                     ->helperText(__('This needs to be checked if the event should be marked as changed!'))
                     ->hidden(fn (string $operation): bool => $operation !== 'edit'),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('timestamp')
                    ->getStateUsing(function ($record) {
                        $suffix = '';
                        if ($record->cancelled) {
                            $suffix = ' - ' . __('Cancelled');
                        } else if ($record->new) {
                            $suffix = ' - ' . __('New');
                        } else  if ($record->hasTimeChanged) {
                            $suffix = ' - ' . __('Changed');
                        }
                        return $record->start->format('H:i') . ' - ' . $record->end->format('H:i') . $suffix;
                    })
                    ->badge(function (Model $record) {
                        return $record->cancelled || $record->new || $record->hasTimeChanged;
                    })
                    ->color(function (Model $record) {
                        if ($record->cancelled) {
                            return 'danger';
                        } else if ($record->new) {
                            return 'info';
                        } else if ($record->hasTimeChanged) {
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
            ])
            ->defaultPaginationPageOption('all')
            ->defaultGroup(
                Group::make('start')
                    ->label('')
                    ->collapsible()
                    ->getTitleFromRecordUsing(fn (Model $record) => Str::upper($record->start->dayName) . ', ' . $record->start->format('d.m.Y'))
            )
            ->filters([
                Tables\Filters\SelectFilter::make('sigLocation')
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
                    ->relationship('sigLocation', 'name', fn (Builder $query) => $query->orderBy('name')),
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

}
