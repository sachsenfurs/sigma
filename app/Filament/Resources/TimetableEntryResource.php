<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TimetableEntryResource\Pages;
use App\Filament\Resources\TimetableEntryResource\Widgets\TimeslotTable;
use App\Models\TimetableEntry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TimetableEntryResource extends Resource
{
    protected static ?string $model = TimetableEntry::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                Forms\Components\Select::make('sig_event_id')
                    ->label(__('Event'))
                    ->relationship('sigEvent', 'name', fn (Builder $query) => $query->orderBy('name'))
                    ->getOptionLabelFromRecordUsing(function (Model $record) {
                        return $record->name . ' - ' . $record->sigLocation->name . ' ' . $record->sigLocation->description;
                    })
                    ->required()
                    ->disabled(fn (string $operation): bool => $operation !== 'create'),
                Forms\Components\Select::make('sig_location_id')
                    ->label(__('Different Location'))
                    ->relationship('sigLocation', 'name', fn (Builder $query) => $query->orderBy('name'))
                    ->getOptionLabelFromRecordUsing(function (Model $record) {
                        // If the location has a description, append it to the name
                        if ($record->description) {
                            return $record->name . ' - ' . $record->description;
                        }
                        return $record->name;
                    }),
                Forms\Components\DateTimePicker::make('start')
                    ->label(__('Beginning'))
                    ->format('Y-m-d\TH:i')
                    ->seconds(false)
                    ->required(),
                Forms\Components\DateTimePicker::make('end')
                    ->label(__('End'))
                    ->format('Y-m-d\TH:i')
                    ->seconds(false)
                    ->required(),
                Forms\Components\Checkbox::make('new')
                    ->label(__('New Event'))
                    ->hidden(fn (string $operation): bool => $operation !== 'edit'),
                Forms\Components\Checkbox::make('cancelled')
                    ->label(__('Event Cancelled'))
                    ->hidden(fn (string $operation): bool => $operation !== 'edit'),
                Forms\Components\Checkbox::make('hide')
                    ->label(__('Internal Event')),
                Forms\Components\Checkbox::make('reset_update')
                    ->label(__('Reset \'Changed\'-flag'))
                    ->hidden(fn (string $operation): bool => $operation !== 'edit'),
                Forms\Components\Checkbox::make('send_update')
                    ->label(__('Announce Changes'))
                    ->helperText(__('This needs to be checked if the event should be marked as changed!'))
                    ->hidden(fn (string $operation): bool => $operation !== 'edit'),
            ]);
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
                        }
                        if ($record->new) {
                            $suffix = ' - ' . __('New');
                        }
                        if ($record->hasTimeChanged) {
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
                        }
                        if ($record->new) {
                            return 'info';
                        }
                        if ($record->hasTimeChanged) {
                            return 'info';
                        }
                        return 'secondary';
                    })
                    ->label(__('Time span')),
                Tables\Columns\TextColumn::make('sigEvent.name')
                    ->label(__('Event')),
                Tables\Columns\TextColumn ::make('sigLocation.name')
                    ->badge()
                    ->label(__('Location')),
            ])
            ->defaultGroup(
                Group::make('start')
                    ->label('')
                    ->collapsible()
                    ->getTitleFromRecordUsing(fn (Model $record) => Str::upper($record->start->dayName) . ', ' . $record->start->format('d.m.Y'))
            )
            ->filters([
                //
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
