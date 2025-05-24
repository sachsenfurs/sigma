<?php

namespace App\Filament\Resources\Ddas\ArtshowItemResource\RelationManagers;

use App\Filament\Resources\Ddas\ArtshowBidResource;
use Filament\Forms;
use Filament\Forms\Components\Field;
use Filament\Forms\Form;
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

    public function form(Form $form): Form {
        return $form->schema(
            collect(ArtshowBidResource::form($form)->getComponents())
                ->filter(fn(Field $e) => $e->getName() != "artshow_item_id")
                ->prepend(
                    Forms\Components\Select::make("artshow_item_id")
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
                Tables\Columns\TextColumn::make("user.reg_id")
                    ->label("Reg Number")
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label("User")
                    ->translateLabel(),
                Tables\Columns\TextColumn::make("value")
                    ->label("Bid")
                    ->translateLabel()
                    ->money(config("app.currency")),
                Tables\Columns\TextColumn::make("created_at")
                    ->label("Created")
                    ->translateLabel()
                    ->dateTime(),
            ])
            ->defaultSort("value", "desc")
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
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
