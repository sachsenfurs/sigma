<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\SigPlanning;
use App\Filament\Resources\TimetableEntryResource\Pages\CreateTimetableEntry;
use App\Filament\Resources\TimetableEntryResource\Pages\ListTimetableEntries;
use App\Models\TimetableEntry;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TimetableEntryResource extends Resource
{
    protected static ?string $model = TimetableEntry::class;

    protected static ?string $cluster = SigPlanning::class;
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

//    public static function form(Form $form): Form
//    {
//        return $form
//            ->schema([
//                Forms\Components\Select::make('sig_event_id')
//                    ->relationship('sigEvent', 'name')
//                    ->required(),
//                Forms\Components\Select::make('sig_location_id')
//                    ->relationship('sigLocation', 'name'),
//                Forms\Components\Select::make('replaced_by_id')
//                    ->relationship('replacedBy', 'id'),
//                Forms\Components\DateTimePicker::make('start')
//                    ->required(),
//                Forms\Components\DateTimePicker::make('end')
//                    ->required(),
//                Forms\Components\Toggle::make('cancelled')
//                    ->required(),
//                Forms\Components\Toggle::make('hide')
//                    ->required(),
//                Forms\Components\Toggle::make('new')
//                    ->required(),
//            ]);
//    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sigEvent.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sigLocation.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('replacedBy.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\IconColumn::make('cancelled')
                    ->boolean(),
                Tables\Columns\IconColumn::make('hide')
                    ->boolean(),
                Tables\Columns\IconColumn::make('new')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
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

//    public static function getRelations(): array
//    {
//        return [
//            //
//        ];
//    }

    public static function getPages(): array
    {
        return [
//            'planner' => SigPlanning\Pages\SigPlanner::route('/planner'),
            'index' => ListTimetableEntries::route('/'),
//
            'create' => CreateTimetableEntry::route('/create'),
//            'view' => SigPlanning\Resources\TimetableEntryResource\Pages\ViewTimetableEntry::route('/{record}'),
//            'edit' => SigPlanning\Resources\TimetableEntryResource\Pages\EditTimetableEntry::route('/{record}/edit'),
        ];
    }

}
