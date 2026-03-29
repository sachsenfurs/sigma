<?php

namespace App\Filament\Resources\Ddas\ArtshowBids;

use App\Filament\Resources\Ddas\ArtshowItems\ArtshowItemResource;
use BackedEnum;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Ddas\ArtshowBids\Pages\ListArtshowBids;
use App\Filament\Helper\FormHelper;
use App\Models\Ddas\ArtshowBid;
use App\Models\Ddas\ArtshowItem;
use App\Settings\ArtShowSettings;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ArtshowBidResource extends Resource
{
    protected static ?string $model = ArtshowBid::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedCurrencyEuro;

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

    public static function form(Schema $schema): Schema {
        return $schema
            ->components([
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
                TextColumn::make("user")
                    ->formatStateUsing(fn(Model $record) => $record->user->name . " #" . $record->user->reg_id)
                    ->label("User")
                    ->translateLabel(),
                TextColumn::make("artshowItem.name")
                    ->label(__("Art Show Item"))
                    ->badge()
                    ->url(fn(Model $record) => ArtshowItemResource::getUrl("edit", ['record' => $record->artshow_item_id])),
                TextColumn::make("value")
                    ->label(__("Value"))
                    ->money(config("app.currency")),
                TextColumn::make("created_at")
                    ->label(__("Created"))
                    ->dateTime(),
            ])
            ->defaultSort("created_at", "desc")
            ->filters([
                //
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

    public static function getRelations(): array {
        return [
            //
        ];
    }

    public static function getPages(): array {
        return [
            'index' => ListArtshowBids::route('/'),
        ];
    }
}
