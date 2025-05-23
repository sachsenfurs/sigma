<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Resources\Ddas\ArtshowBidResource;
use App\Filament\Resources\Ddas\ArtshowItemResource;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ArtshowBidsRelationManager extends RelationManager
{
    protected static string $relationship = 'artshowBids';
    protected static ?string $icon = 'heroicon-o-currency-euro';


    public static function getBadge(Model $ownerRecord, string $pageClass): ?string {
        return $ownerRecord->artshowBids()->count();
    }

    public static function getModelLabel(): string {
        return __("Bid");
    }

    public static function getModelPluralLabel(): ?string {
        return __("Bids");
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string {
        return __("Bids");
    }

    public function form(Form $form): Form {
        return ArtshowBidResource::form($form);
    }

    public function table(Table $table): Table {
        return $table
            ->recordTitleAttribute('artshowItem.name')
            ->columns([
                Tables\Columns\TextColumn::make('artshowItem.artist.name')
                    ->label("Artist")
                    ->translateLabel()
                    ->sortable(),
                Tables\Columns\TextColumn::make('value')
                    ->label("Bid")
                    ->translateLabel()
                    ->money(config("app.currency"))
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label("Created")
                    ->translateLabel()
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
                Tables\Actions\CreateAction::make()
                    ->modelLabel(__("Bid"))
                    ->fillForm([
                        'user_id' => $this->ownerRecord->id
                    ]),
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
