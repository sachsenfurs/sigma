<?php

namespace App\Filament\Resources\Ddas;

use App\Filament\Helper\FormHelper;
use App\Filament\Resources\Ddas\ArtshowBidResource\Pages;
use App\Models\Ddas\ArtshowBid;
use App\Models\Ddas\ArtshowItem;
use App\Settings\ArtShowSettings;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Placeholder;
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
                    ->relationship("artshowItem", "name")
                    ->getOptionLabelFromRecordUsing(fn(Model $record) => $record->id . " - " . $record->name)
                    ->columnSpanFull()
                    ->searchable(['id', 'name'])
                    ->preload()
                    ->live()
                    ->required()
                    ->helperText(fn(Get $get) => __("Current Bid") . ": " . (ArtshowItem::find($get("artshow_item_id"))?->highestBid->value ?? 0)),
                Select::make("user_id")
                    ->relationship("user", "name")
                    ->getOptionLabelFromRecordUsing(FormHelper::formatUserWithRegId())
                    ->searchable(['reg_id', 'name']),
                TextInput::make("value")
                    ->numeric()
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->modifyQueryUsing(fn($query) => $query->with(["user"]))
            ->columns([
                Tables\Columns\TextColumn::make("user")
                    ->formatStateUsing(fn(Model $record) => $record->user->name . " #" . $record->user->reg_id),
                Tables\Columns\TextColumn::make("artshowItem.name"),
                Tables\Columns\TextColumn::make("value"),
                Tables\Columns\TextColumn::make("created_at")
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
//            'create' => Pages\CreateArtshowBid::route('/create'),
            'edit' => Pages\EditArtshowBid::route('/{record}/edit'),
        ];
    }
}
