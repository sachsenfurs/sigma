<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Resources\Ddas\ArtshowItemResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ArtshowBidsRelationManager extends RelationManager
{
    protected static string $relationship = 'artshowBids';

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string {
        return $ownerRecord->artshowBids()->count();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('value')
                    ->numeric()
                    ->required()
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('artshowItem.name')
            ->columns([
                Tables\Columns\TextColumn::make('artshowItem.artist.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('value')
                    ->money("EUR")
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->dateTime(),
            ])
            ->defaultGroup(
                Group::make('artshowItem.id')
                    ->label('')
                    ->collapsible()
                    ->getTitleFromRecordUsing(fn($record) => $record->artshowItem->name)
            )
            ->defaultSort("created_at", "desc")
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->recordUrl(fn(Model $record) => ArtshowItemResource::getUrl("edit", ['record' => $record->artshowItem]))
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
