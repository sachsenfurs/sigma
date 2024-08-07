<?php

namespace App\Filament\Resources\Ddas;

use App\Enums\Approval;
use App\Filament\Actions\TranslateAction;
use App\Filament\Resources\Ddas\ArtshowItemResource\Pages;
use App\Filament\Resources\Ddas\ArtshowItemResource\RelationManagers\ArtshowBidsRelationManager;
use App\Models\Ddas\ArtshowItem;
use App\Settings\ArtShowSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
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

        return ArtshowItem::whereApproval(Approval::PENDING)->count() ?: null;
    }

    public static function form(Form $form): Form {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Item Name')
                    ->translateLabel()
                    ->maxLength(255),
                Forms\Components\Radio::make('approval')
                    ->label('Approval')
                    ->translateLabel()
                    ->default(Approval::PENDING)
                    ->options(Approval::class)
                    ->required(),
                Forms\Components\Grid::make()
                     ->columns(1)
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
                    ->toggleable(),
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
            ])
            ->actions([])
            ->headerActions([])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Approval::getBulkAction(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
