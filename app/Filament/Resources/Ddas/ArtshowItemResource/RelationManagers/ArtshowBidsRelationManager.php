<?php

namespace App\Filament\Resources\Ddas\ArtshowItemResource\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Ddas\ArtshowBidResource;
use Filament\Forms;
use Filament\Forms\Components\Field;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ArtshowBidsRelationManager extends RelationManager
{
    protected static string $relationship = 'artshowBids';

    public static function getModelLabel(): ?string {
        return __("Bid");
    }

    public static function getPluralModelLabel(): ?string {
        return __("Bids");
    }


    public static function getTitle(Model $ownerRecord, string $pageClass): string {
        return __("Bids");
    }

    public function form(Schema $schema): Schema {
        return $schema->components(
            collect(ArtshowBidResource::form($schema)->getComponents())
                ->filter(fn(Field $e) => $e->getName() != "artshow_item_id")
                ->prepend(
                    Select::make("artshow_item_id")
                        ->relationship("artshowItem", "name")
                        ->default($this->ownerRecord->id)
                        ->columnSpanFull()
                        ->disabled()
                )
                ->toArray()
        );
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user.name')
            ->columns([
                TextColumn::make("user.reg_id")
                    ->label("Reg Number")
                    ->translateLabel(),
                TextColumn::make('user.name')
                    ->label("User")
                    ->translateLabel(),
                TextColumn::make("value")
                    ->label("Bid")
                    ->translateLabel()
                    ->money(config("app.currency")),
                TextColumn::make("created_at")
                    ->label("Created")
                    ->translateLabel()
                    ->dateTime(),
            ])
            ->defaultSort("value", "desc")
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
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
