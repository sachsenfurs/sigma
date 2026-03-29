<?php

namespace App\Filament\Resources\LostFoundItems\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LostFoundItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('lassie_id')
                    ->numeric(),
                ImageColumn::make('thumb_url')
                    ->label(__("Image")),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('lost_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('found_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('returned_at')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make("returned_at")
                    ->label(__("Returned"))
                    ->default(false)
                    ->queries(
                        true: fn(Builder $query) => $query->whereNotNull("returned_at"),
                        false: fn(Builder $query) => $query->whereNull("returned_at"),
                        blank: fn(Builder $query) => $query,
                    )
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
