<?php

namespace App\Filament\Resources\Ddas;

use App\Filament\Helper\FormHelper;
use App\Filament\Resources\Ddas\ArtshowBidResource\Pages;
use App\Models\Ddas\ArtshowBid;
use App\Models\Ddas\ArtshowItem;
use App\Settings\ArtShowSettings;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ArtshowBidResource extends Resource
{
    protected static ?string $model = ArtshowBid::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-euro';

    protected static ?int $navigationSort = 220;

    /**
     * @return string|null
     */
    public static function getLabel(): ?string {
        return __("Bid");
    }
    public static function getPluralLabel(): ?string {
        return __('Bids');
    }

    public static function getNavigationGroup(): ?string {
        return __("Art Show");
    }

    public static function canAccess(): bool {
        return parent::canAccess() AND app(ArtShowSettings::class)->enabled;
    }

    public static function form(Form $form): Form {
        return $form
            ->schema([
                Select::make("artshow_item_id")
                    ->label("Art Show Item")
                    ->translateLabel()
                    ->relationship("artshowItem", "name")
                    ->getOptionLabelFromRecordUsing(fn(Model $record) => $record->id . " - " . $record->name)
                    ->columnSpanFull()
                    ->searchable(['id', 'name'])
                    ->preload()
                    ->live()
                    ->debounce()
                    ->required()
                    ->helperText(function (Get $get) {
                        if($item = ArtshowItem::find($get("artshow_item_id"))) {
                            if($bid = $item->highestBid)
                                return __("Current Bid") . ": " . $bid->value . " ({$bid->user->name})";
                        }
                        return null;
                    }),
                Select::make("user_id")
                    ->label("User")
                    ->translateLabel()
                    ->required()
                    ->relationship("user", "name")
                    ->getOptionLabelFromRecordUsing(FormHelper::formatUserWithRegId())
                    ->searchable(['reg_id', 'name']),
                TextInput::make("value")
                    ->label("Bid")
                    ->translateLabel()
                    ->required()
                    ->numeric()
                    ->live()
                    ->debounce()
                    ->minValue(function (Get $get, $operation) {
                        if($operation == "edit")
                            return 0;
                        return ArtshowItem::find($get("artshow_item_id"))?->minBidValue() ?? 0;
                    })
                    ->prefixIcon("heroicon-o-currency-euro")
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->modifyQueryUsing(fn($query) => $query->with(["user"]))
            ->columns([
                Tables\Columns\TextColumn::make("user")
                    ->formatStateUsing(fn(Model $record) => $record->user->name . " #" . $record->user->reg_id)
                    ->label("User")
                    ->translateLabel(),
                Tables\Columns\TextColumn::make("artshowItem.name")
                    ->label(__("Art Show Item"))
                    ->badge()
                    ->url(fn(Model $record) => ArtshowItemResource::getUrl("edit", ['record' => $record->artshow_item_id])),
                Tables\Columns\TextColumn::make("value")
                    ->label(__("Value"))
                    ->money(config("app.currency")),
                Tables\Columns\TextColumn::make("created_at")
                    ->label(__("Created"))
                    ->dateTime(),
            ])
            ->defaultSort("created_at", "desc")
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

    public static function getRelations(): array {
        return [
            //
        ];
    }

    public static function getPages(): array {
        return [
            'index' => Pages\ListArtshowBids::route('/'),
        ];
    }
}
