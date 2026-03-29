<?php

namespace App\Filament\Resources\Ddas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Schemas\Components\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkAction;
use App\Filament\Resources\Ddas\DealerResource\Pages\ListDealers;
use App\Filament\Resources\Ddas\DealerResource\Pages\CreateDealer;
use App\Filament\Resources\Ddas\DealerResource\Pages\ViewDealer;
use App\Filament\Resources\Ddas\DealerResource\Pages\EditDealer;
use App\Enums\Approval;
use App\Filament\Actions\TranslateAction;
use App\Filament\Helper\FormHelper;
use App\Filament\Resources\ChatResource;
use App\Filament\Resources\Ddas\DealerResource\Pages;
use App\Models\Ddas\Dealer;
use App\Models\Ddas\DealerTag;
use App\Models\SigLocation;
use App\Settings\DealerSettings;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class DealerResource extends Resource
{
    protected static ?string $model = Dealer::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-shopping-cart';

    protected static string | \UnitEnum | null $navigationGroup = "Dealer's Den";

    protected static ?int $navigationSort = 300;

    public static function getPluralLabel(): ?string {
        return __('Dealers');
    }
    public static function getLabel(): ?string {
        return __("Dealer");
    }

    public static function canAccess(): bool {
        return parent::canAccess() AND app(DealerSettings::class)->enabled;
    }


    public static function getNavigationBadge(): ?string {
        if(!Route::is("filament.*") AND !Route::is("livewire.*"))
            return null;

        return Cache::remember("dealer-unapproved-badge", 10, fn() => Dealer::whereApproval(Approval::PENDING)->count()) ?: null;
    }


    public static function form(Schema $schema): Schema {
        return $schema
            ->components([
                Fieldset::make("User Details")
                    ->translateLabel()
                    ->columnSpanFull()
                    ->schema([
                        Select::make('user_id')
                            ->label('User')
                            ->searchable(['id', 'name'])
                            ->getOptionLabelFromRecordUsing(FormHelper::formatUserWithRegId())
                            ->translateLabel()
                            ->relationship('user', 'name')
                            ->columnSpan(1),
                        Grid::make()
                            ->columnSpanFull()
                            ->columnSpan(1)
                            ->schema([
                                    Radio::make('approval')
                                        ->translateLabel()
                                        ->options(Approval::class)
                                        ->required(),
                                    Actions::make([
                                            ChatResource::getCreateChatAction(fn(Model $record) => $record?->user_id),
                                        ])
                                       ->alignCenter()
                                       ->verticallyAlignCenter()
                                       ->visibleOn("edit")
                            ]),
                    ]),
                Fieldset::make("Dealer Details")
                    ->translateLabel()
                    ->columns(3)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name')
                            ->label('Dealer Name')
                            ->translateLabel()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('gallery_link')
                            ->label("Gallery Link, Website, ...")
                            ->translateLabel()
                            ->maxLength(255),
                        Select::make('sig_location_id')
                            ->label('Location')
                            ->translateLabel()
                            ->relationship('sigLocation')
                            ->getOptionLabelFromRecordUsing(FormHelper::formatLocationWithDescription()),

                        Grid::make()
                            ->columns(2)
                            ->columnSpanFull()
                            ->schema([
                                Textarea::make('info')
                                    ->label('Dealer Info')
                                    ->rows(8)
                                    ->translateLabel()
                                    ->maxLength(65535)
                                    ->hintAction(
                                        fn($operation) => $operation != "view" ? TranslateAction::translateToPrimary('info_en', 'info')->authorize("create", Dealer::class) : null
                                    )
                                    ->columnSpan([
                                        'lg' => 2,
                                        '2xl' => 1
                                    ]),
                                Textarea::make('info_en')
                                    ->label('Dealer Info (English)')
                                    ->rows(8)
                                    ->translateLabel()
                                    ->maxLength(65535)
                                    ->hintAction(
                                        fn($operation) => $operation != "view" ? TranslateAction::translateToSecondary('info', 'info_en')->authorize("create", Dealer::class) : null
                                    )
                                    ->columnSpan([
                                        'lg' => 2,
                                        '2xl' => 1
                                    ]),
                                Textarea::make("additional_info")
                                    ->label("Additional Information")
                                    ->translateLabel()
                                    ->autosize()
                                    ->columnSpanFull(),
                                FileUpload::make('icon_file')
                                    ->label('Logo')
                                    ->translateLabel()
                                    ->disk("public")
                                    ->preserveFilenames(false)
                                    ->imageCropAspectRatio("1:1")
                                    ->image()
                                    ->imageEditor()
                                    ->downloadable()
                                    ->maxFiles(1)
                                    ->maxSize(5120),
                                Select::make("tags")
                                    ->label("Tags")
                                    ->columns(1)
                                    ->translateLabel()
                                    ->relationship("tags")
                                    ->getOptionLabelFromRecordUsing(FormHelper::dealerTagLocalized())
                                    ->multiple()
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm(DealerTagResource::getSchema()),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->with("tags"))
            ->columns([
                ImageColumn::make('icon_file')
                    ->label('Dealer Logo')
                    ->square()
                    ->translateLabel()
                    ->extraHeaderAttributes([
                        'class' => "w-8"
                    ])
                    ->toggleable(),
                TextColumn::make('name')
                    ->label('Dealer Name')
                    ->translateLabel()
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Benutzername')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                TextColumn::make("additional_info")
                    ->label("Additional Information")
                    ->translateLabel()
                    ->view("filament.resources.ddas.additional-info")
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->wrap(),
                TextColumn::make("tags")
                    ->formatStateUsing(fn(Model $state) => $state->name_localized)
                    ->translateLabel()
                    ->toggleable()
                    ->badge(),
                IconColumn::make('approval')
                    ->label("Approved")
                    ->translateLabel(),
                TextColumn::make('sigLocation.name')
                    ->label('Location')
                    ->translateLabel()
                    ->toggleable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make("approval")
                    ->label("Approval")
                    ->translateLabel()
                    ->options(Approval::class),
                SelectFilter::make("tags")
                    ->relationship("tags", "name")
                    ->getOptionLabelFromRecordUsing(fn(DealerTag $record) => $record->name_localized),
                SelectFilter::make("sigLocation")
                    ->relationship("sigLocation", "name", fn(Builder $query) => $query->whereIn("id", Dealer::pluck("sig_location_id")))
                    ->getOptionLabelFromRecordUsing(fn(SigLocation $record) => $record->name_localized)
                    ->label("Location")
                    ->translateLabel(),
            ])
            ->recordActions([
                EditAction::make()->modalWidth("7xl"),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    Approval::getBulkAction()
                        ->authorize("updateAny", Dealer::class),
                    BulkAction::make("sigLocation")
                        ->authorize("updateAny", Dealer::class)
                        ->label("Set Location")
                        ->translateLabel()
                        ->schema([
                            Select::make("sig_location_id")
                                ->label("Location")
                                ->translateLabel()
                                ->relationship("sigLocation", "name")
                                ->getOptionLabelFromRecordUsing(FormHelper::formatLocationWithDescription()),
                        ])
                        ->action(
                            function(array $data, Collection $records) {
                                $records->each->update($data);
                            }
                        ),
                ]),
            ])
            ->defaultSort(fn(Builder $query): Builder => $query->orderBy("approval")->orderBy("name"))
            ->recordAction(EditAction::class)
            ->recordUrl(function(Model $record) {
                return self::canEdit($record) ? null : self::getUrl('view', ['record' => $record]);
            });
    }

    public static function getRelations(): array {
        return [
            //
        ];
    }

    public static function getPages(): array {
        return [
            'index' => ListDealers::route('/'),
            'create' => CreateDealer::route('/create'),
            'view' => ViewDealer::route('/{record}'),
            'edit' => EditDealer::route('/{record}/edit'),
        ];
    }
}
