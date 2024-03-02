<?php

namespace App\Filament\Resources\SigTagResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SigTagsRelationManager extends RelationManager
{
    protected static string $relationship = 'sigEvent';

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('Assigned SIGs'))
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('sigHost.name')
                    ->label('Host')
                    ->translateLabel()
                    ->searchable()
                    ->formatStateUsing(function (Model $record) {
                        $regNr = $record->sigHost->reg_id ? ' (' . __('Reg Number') . ': ' . $record->sigHost->reg_id . ')' : '';
                        return $record->sigHost->name . $regNr;
                    })
                    ->sortable(),
                Tables\Columns\ImageColumn::make('languages')
                    ->label('Languages')
                    ->translateLabel()
                    ->view('filament.tables.columns.sig-event.flag-icon'),
                Tables\Columns\TextColumn::make('sigLocation.name')
                    ->label('Location')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
            ])
            ->defaultPaginationPageOption('25')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(fn ($record) => route('filament.admin.resources.sig-events.edit', $record)),
            ]);
    }
}
