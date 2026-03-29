<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Ddas\ArtshowBidResource;
use App\Filament\Resources\Ddas\ArtshowItemResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ArtshowBidsRelationManager extends RelationManager
{
    protected static string $relationship = 'artshowBids';
    protected static string | \BackedEnum | null $icon = 'heroicon-o-currency-euro';


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

    public function form(Schema $schema): Schema {
        return ArtshowBidResource::form($schema);
    }

    public function table(Table $table): Table {
        return $table
            ->recordTitleAttribute('artshowItem.name')
            ->columns([
                TextColumn::make('artshowItem.artist.name')
                    ->label("Artist")
                    ->translateLabel()
                    ->sortable(),
                TextColumn::make('value')
                    ->label("Bid")
                    ->translateLabel()
                    ->money(config("app.currency"))
                    ->sortable(),
                TextColumn::make('created_at')
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
                CreateAction::make()
                    ->modelLabel(__("Bid"))
                    ->fillForm([
                        'user_id' => $this->ownerRecord->id
                    ]),
            ])
            ->recordUrl(fn(Model $record) => ArtshowItemResource::getUrl("edit", ['record' => $record->artshowItem]))
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
