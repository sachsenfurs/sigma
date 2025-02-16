<?php

namespace App\Filament\Resources\Ddas;

use App\Enums\Approval;
use App\Enums\Rating;
use App\Filament\Actions\TranslateAction;
use App\Filament\Helper\FormHelper;
use App\Filament\Resources\ChatResource;
use App\Filament\Resources\Ddas\ArtshowItemResource\Pages;
use App\Filament\Resources\Ddas\ArtshowItemResource\RelationManagers\ArtshowBidsRelationManager;
use App\Models\Ddas\ArtshowItem;
use App\Settings\ArtShowSettings;
use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class ArtshowItemResource extends Resource
{
    protected static ?string $model = ArtshowItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?int $navigationSort = 210;

    public static function getLabel(): ?string {
        return __("Item");
    }

    public static function getPluralLabel(): ?string {
        return __('Items');
    }
    public static function getNavigationLabel(): string {
        return __("Items");
    }
    public static function getNavigationGroup(): ?string {
        return __("Art Show");
    }

    public static function canAccess(): bool {
        return parent::canAccess() AND app(ArtShowSettings::class)->enabled;
    }

    public static function getNavigationBadge(): ?string {
        if(!Route::is("filament.*") AND !Route::is("livewire.*"))
            return null;

        return Cache::remember("artshow-unapproved-badge", 10, fn() => ArtshowItem::whereApproval(Approval::PENDING)->count()) ?: null;
    }

    public static function form(Form $form): Form {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Item Name')
                            ->translateLabel()
                            ->columnSpan(1)
                            ->maxLength(255),
                        Forms\Components\Fieldset::make("")
                            ->columnSpan(1)
                            ->schema([
                                Forms\Components\Radio::make('approval')
                                    ->label('Approval')
                                    ->translateLabel()
                                    ->default(Approval::PENDING)
                                    ->options(Approval::class)
                                    ->required(),
                                Actions::make([
                                        ChatResource::getCreateChatAction(fn(Model $record) => $record?->artist?->user_id),
                                    ])
                                    ->alignCenter()
                                    ->verticallyAlignCenter()
                                    ->visibleOn("edit"),
                            ]),
                    ]),
                Forms\Components\Grid::make()
                     ->columns([
                         'lg' => 1,
                         '2xl' => 2
                     ])
                     ->schema([
                            Forms\Components\RichEditor::make('description')
                                ->label('Description')
                                ->translateLabel()
                                ->maxLength(65535)
                                ->hintAction(
                                    fn($operation, $record) => $operation != "view" ? TranslateAction::translateToPrimary('description_en', 'description')->authorize("create", ArtshowItem::class) : null
                                ),
                            Forms\Components\RichEditor::make('description_en')
                                ->label('Description (English)')
                                ->translateLabel()
                                ->maxLength(65535)
                                ->hintAction(
                                    fn($operation) => $operation != "view" ? TranslateAction::translateToSecondary('description', 'description_en')->authorize("create", ArtshowItem::class): null
                                ),
                     ]),
                Forms\Components\TextInput::make('starting_bid')
                    ->label('Starting Bid')
                    ->translateLabel()
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('charity_percentage')
                    ->label('Charity Percentage')
                    ->translateLabel()
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Textarea::make('additional_info')
                    ->label('Additional Information')
                    ->translateLabel()
                    ->maxLength(65535)
                    ->rows(5)
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('image')
                    ->label('Image')
                    ->translateLabel()
                    ->preserveFilenames(false)
                    ->disk('public')
                    ->image()
                    ->downloadable()
                    ->imageEditor()
                    ->maxFiles(1)
                    ->maxSize(5120),
                Forms\Components\Radio::make("rating")
                    ->options(Rating::class)
                    ->default(Rating::SFW)
                    ->required(),
                Forms\Components\Checkbox::make('locked')
                    ->label('Locked')
                    ->translateLabel(),
            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("id")
                    ->searchable(),
                Tables\Columns\IconColumn::make('approval')
                    ->label('Approval')
                    ->translateLabel()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('artist.name')
                    ->label('Artist Name')
                    ->translateLabel()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image')
                    ->translateLabel()
                    ->height("4rem")
                    ->alignCenter()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('rating')
                    ->badge(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Item Name')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('starting_bid')
                    ->label('Starting Bid')
                    ->translateLabel()
                    ->formatStateUsing(fn(string $state): string => "€ " . (int)$state)
                    ->sortable(),
                Tables\Columns\TextColumn::make('charity_percentage')
                    ->label('Charity Percentage')
                    ->translateLabel()
                    ->formatStateUsing(fn(string $state): string => (int)$state . " %")
                    ->sortable(),
                Tables\Columns\TextColumn::make("artshow_bids_count")
                    ->label("Bid Count")
                    ->translateLabel()
                    ->counts("artshowBids")
                    ->sortable(),
                Tables\Columns\TextColumn::make("highestBid.value")
                    ->label("Current Bid")
                    ->money("EUR")
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make("highestBid.user.name")
                    ->label("Highest Bidder")
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->translateLabel(),
                Tables\Columns\IconColumn::make('auction')
                    ->label('Auction')
                    ->translateLabel()
                    ->boolean()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('sold')
                    ->label('Sold')
                    ->translateLabel()
                    ->boolean()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('paid')
                    ->label('Paid')
                    ->translateLabel()
                    ->boolean()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make("approval")
                   ->label("Approval")
                   ->translateLabel()
                   ->options(Approval::class),
                Tables\Filters\SelectFilter::make("highestBid")
                    ->label("Highest Bidder")
                    ->translateLabel()
                    ->relationship("highestBid.user", "name")
                    ->searchable()
                    ->getSearchResultsUsing(FormHelper::searchUserByNameAndRegId()),
                Tables\Filters\TernaryFilter::make("paid")
                    ->label("Paid")
                    ->translateLabel(),
            ])
            ->actions([])
            ->headerActions([])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Approval::getBulkAction(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                Tables\Actions\BulkAction::make("pickup")
                    ->infolist(function(Collection $records) {
                        $entries = [];
                        foreach($records AS $record) {
                            $entries[] = Fieldset::make("item_info")
                                ->label($record->name)
                                ->schema([
                                    TextEntry::make("id")
                                        ->state($record->id)
                                        ->inlineLabel()
                                        ->columns(6),
                                    TextEntry::make("paid")
                                        ->state($record->paid ? "THIS ITEM IS ALREADY PAID!" : "Not paid")
                                        ->color($record->paid ? Color::Red : Color::Neutral)
                                        ->inlineLabel()
                                        ->columns(6),
                                    TextEntry::make("artist")
                                         ->state($record->artist->name)
                                         ->inlineLabel(),
                                    TextEntry::make("Current Bid")
                                         ->state($record->highestBid?->value ?? 0)
                                         ->money("EUR")
                                         ->inlineLabel()
                                ]);
                        }
                        $entries[] = TextEntry::make("sum")
                            ->state($records->sum("highestBid.value"))
                            ->money("EUR")
                            ->size(TextEntry\TextEntrySize::Large)
                            ->inlineLabel();
                        return $entries;
                    })
                    ->action(function(Tables\Actions\BulkAction $action, Collection $records) {
                        $records->each->update([
                            'paid' => true,
                            'sold' => true,
                        ]);
                    }),

            ]);
    }


    public static function getRelations(): array {
        return [
            ArtshowBidsRelationManager::class,
        ];
    }

    public static function getPages(): array {
        return [
            'index' => Pages\ListArtshowItems::route('/'),
//            'create' => Pages\CreateArtshowItem::route('/create'),
            'edit' => Pages\EditArtshowItem::route('/{record}/edit'),
        ];
    }
}
