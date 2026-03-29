<?php

namespace App\Filament\Resources\Ddas\ArtshowItems;

use BackedEnum;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Forms\Components\Radio;
use Filament\Schemas\Components\Actions;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Checkbox;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkAction;
use Filament\Support\Enums\TextSize;
use App\Filament\Resources\Ddas\ArtshowItems\Pages\ListArtshowItems;
use App\Filament\Resources\Ddas\ArtshowItems\Pages\EditArtshowItem;
use App\Enums\Approval;
use App\Enums\Rating;
use App\Filament\Actions\TranslateAction;
use App\Filament\Helper\FormHelper;
use App\Filament\Infolists\Components\Alert;
use App\Filament\Resources\Chats\ChatResource;
use App\Filament\Resources\Ddas\ArtshowItems\RelationManagers\ArtshowBidsRelationManager;
use App\Models\Ddas\ArtshowItem;
use App\Settings\ArtShowSettings;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class ArtshowItemResource extends Resource
{
    protected static ?string $model = ArtshowItem::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-cube';

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

    public static function form(Schema $schema): Schema {
        return $schema
            ->components([
                Grid::make()
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name')
                            ->label('Item Name')
                            ->translateLabel()
                            ->columnSpan(1)
                            ->maxLength(255),
                        Fieldset::make("")
                            ->columnSpanFull()
                            ->columnSpan(1)
                            ->schema([
                                Radio::make('approval')
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
                Grid::make()
                     ->columns([
                         'lg' => 1,
                         '2xl' => 2
                     ])
                     ->columnSpanFull()
                     ->schema([
                            MarkdownEditor::make('description')
                                ->label('Description')
                                ->translateLabel()
                                ->maxLength(65535)
                                ->hintAction(
                                    fn($operation, $record) => $operation != "view" ? TranslateAction::translateToPrimary('description_en', 'description')->authorize("create", ArtshowItem::class) : null
                                ),
                            MarkdownEditor::make('description_en')
                                ->label('Description (English)')
                                ->translateLabel()
                                ->maxLength(65535)
                                ->hintAction(
                                    fn($operation) => $operation != "view" ? TranslateAction::translateToSecondary('description', 'description_en')->authorize("create", ArtshowItem::class): null
                                ),
                     ]),
                Checkbox::make("auction")
                    ->columnSpanFull()
                    ->label(__("Up for auction"))
                    ->reactive(),
                TextInput::make('starting_bid')
                    ->label('Starting Bid')
                    ->translateLabel()
                    ->required()
                    ->numeric()
                    ->visible(fn(Get $get) => $get('auction'))
                    ->default(0),
                TextInput::make('charity_percentage')
                    ->label('Charity Percentage')
                    ->translateLabel()
                    ->required()
                    ->numeric()
                    ->visible(fn(Get $get) => $get('auction'))
                    ->default(0),
                Textarea::make('additional_info')
                    ->label('Additional Information')
                    ->translateLabel()
                    ->maxLength(65535)
                    ->rows(5)
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->label('Image')
                    ->translateLabel()
                    ->preserveFilenames(false)
                    ->disk('public')
                    ->image()
                    ->downloadable()
                    ->imageEditor()
                    ->maxFiles(1)
                    ->maxSize(5120),
                Radio::make("rating")
                    ->options(Rating::class)
                    ->default(Rating::SFW)
                    ->required(),
                Checkbox::make('locked')
                    ->label('Locked')
                    ->translateLabel(),
            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns([
                TextColumn::make("id")
                    ->label("ID")
                    ->searchable(),
                IconColumn::make('approval')
                    ->label('Approval')
                    ->translateLabel()
                    ->toggleable(),
                TextColumn::make('artist.name')
                    ->label('Artist Name')
                    ->translateLabel()
                    ->sortable(),
                ImageColumn::make('image')
                    ->label('Image')
                    ->translateLabel()
                    ->imageHeight("4rem")
                    ->alignCenter()
                    ->toggleable(),
                TextColumn::make('rating')
                    ->badge(),
                TextColumn::make('name')
                    ->label('Item Name')
                    ->translateLabel()
                    ->searchable(),
                TextColumn::make('starting_bid')
                    ->label('Starting Bid')
                    ->translateLabel()
                    ->formatStateUsing(fn(string $state): string => config("app.currency_symbol") . " " . (int)$state)
                    ->sortable(),
                TextColumn::make('charity_percentage')
                    ->label('Charity Percentage')
                    ->translateLabel()
                    ->formatStateUsing(fn(string $state): string => (int)$state . " %")
                    ->sortable(),
                TextColumn::make("artshow_bids_count")
                    ->label("Bid Count")
                    ->translateLabel()
                    ->counts("artshowBids")
                    ->sortable(),
                TextColumn::make("highestBid.value")
                    ->label("Current Bid")
                    ->money(config("app.currency"))
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make("highestBid.user.name")
                    ->label("Highest Bidder")
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->translateLabel(),
                IconColumn::make('auction')
                    ->label('Auction')
                    ->translateLabel()
                    ->boolean()
                    ->toggleable(),
                IconColumn::make('sold')
                    ->label('Sold')
                    ->translateLabel()
                    ->boolean()
                    ->toggleable(),
                IconColumn::make('paid')
                    ->label('Paid')
                    ->translateLabel()
                    ->boolean()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make("approval")
                   ->label("Approval")
                   ->translateLabel()
                   ->options(Approval::class),
                SelectFilter::make("highestBid")
                    ->label("Highest Bidder")
                    ->translateLabel()
                    ->relationship("highestBid.user", "name")
                    ->searchable()
                    ->getSearchResultsUsing(FormHelper::searchUserByNameAndRegId()),
                TernaryFilter::make("paid")
                    ->label("Paid")
                    ->default(false)
                    ->translateLabel(),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->recordActions([])
            ->headerActions([])
            ->toolbarActions([
                BulkActionGroup::make([
                    Approval::getBulkAction()
                        ->authorize("update"),
                    DeleteBulkAction::make(),
                ]),
                BulkAction::make("pickup")
                    ->authorize("create")
                    ->schema(function(Collection $records) {
                        $entries = [];
                        $differentUsers = false;
                        $lastUser = null;
                        foreach($records AS $record) {
                            if($lastUser == null)
                                $lastUser = $record->highestBid?->user_id;
                            elseif($lastUser != $record->highestBid?->user_id)
                                $differentUsers = true;

                            $entries[] = Grid::make(3)
                                    ->columnSpanFull()
                                    ->schema([
                                        Fieldset::make("item_info")
                                            ->columnSpanFull()
                                            ->columnSpan(2)
                                            ->label($record->name)
                                            ->schema([
                                                TextEntry::make("id")
                                                    ->label("ID")
                                                    ->state($record->id)
                                                    ->inlineLabel()
                                                    ->size(TextSize::Large),
                                                TextEntry::make("paid")
                                                    ->label("Paid")
                                                    ->translateLabel()
                                                    ->state($record->paid ? "THIS ITEM IS ALREADY PAID!" : "Not paid")
                                                    ->color($record->paid ? Color::Red : Color::Neutral)
                                                    ->inlineLabel(),
                                                TextEntry::make("charity_percentage")
                                                    ->label("Charity %")
                                                    ->state($record->charity_percentage)
                                                    ->suffix("%")
                                                    ->inlineLabel(),
                                                TextEntry::make("charity_amount")
                                                    ->label(__("Charity Amount"))
                                                    ->state(($record->highestBid?->value ?? 0) * ($record->charity_percentage/100))
                                                    ->money(config("app.currency"))
                                                    ->inlineLabel(),
                                                TextEntry::make("artist")
                                                     ->state($record->artist->name)
                                                     ->inlineLabel(),
                                                TextEntry::make("Current Bid")
                                                     ->label(__("Current Bid"))
                                                     ->state($record->highestBid?->value ?? 0)
                                                     ->money(config("app.currency"))
                                                     ->inlineLabel(),
                                            ]),
                                        ImageEntry::make("image")
                                            ->state($record)
                                            ->hiddenLabel()
                                            ->alignCenter()
                                            ->defaultImageUrl($record->image_url),
                                    ]);
                        }
                        if($differentUsers)
                            $entries[] = Alert::make("warning")
                                ->columnSpanFull()
                                ->subText(__("You have selected multiple items from different users!"))
                                ->state(__("Different users detected!"));
                        $entries[] = TextEntry::make("sum")
                            ->label(__("Sum"))
                            ->state($records->sum("highestBid.value"))
                            ->money(config("app.currency"))
                            ->size(TextSize::Large)
                            ->inlineLabel();
                        return $entries;
                    })
                    ->action(function(BulkAction $action, Collection $records) {
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
            'index' => ListArtshowItems::route('/'),
//            'create' => Pages\CreateArtshowItem::route('/create'),
            'edit' => EditArtshowItem::route('/{record}/edit'),
        ];
    }
}
