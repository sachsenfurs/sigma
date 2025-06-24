<?php

namespace App\Filament\Resources\Ddas;

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
use Filament\Forms\Components\Actions;
use Filament\Forms\Form;
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

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = "Dealer's Den";

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


    public static function form(Form $form): Form {
        return $form
            ->schema([
                Forms\Components\Fieldset::make("User Details")
                    ->translateLabel()
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('User')
                            ->searchable(['id', 'name'])
                            ->getOptionLabelFromRecordUsing(FormHelper::formatUserWithRegId())
                            ->translateLabel()
                            ->relationship('user', 'name')
                            ->columnSpan(1),
                        Forms\Components\Grid::make()
                            ->columnSpan(1)
                            ->schema([
                                    Forms\Components\Radio::make('approval')
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
                Forms\Components\Fieldset::make("Dealer Details")#
                    ->translateLabel()
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Dealer Name')
                            ->translateLabel()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('gallery_link')
                            ->label("Gallery Link, Website, ...")
                            ->translateLabel()
                            ->maxLength(255),
                        Forms\Components\Select::make('sig_location_id')
                            ->label('Location')
                            ->translateLabel()
                            ->relationship('sigLocation')
                            ->getOptionLabelFromRecordUsing(FormHelper::formatLocationWithDescription()),

                        Forms\Components\Grid::make()
                            ->columns(2)
                            ->schema([
                                Forms\Components\Textarea::make('info')
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
                                Forms\Components\Textarea::make('info_en')
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
                                Forms\Components\Textarea::make("additional_info")
                                    ->label("Additional Information")
                                    ->translateLabel()
                                    ->autosize()
                                    ->columnSpanFull(),
                                Forms\Components\FileUpload::make('icon_file')
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
                                Forms\Components\Select::make("tags")
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
                Tables\Columns\ImageColumn::make('icon_file')
                    ->label('Dealer Logo')
                    ->square()
                    ->translateLabel()
                    ->extraHeaderAttributes([
                        'class' => "w-8"
                    ])
                    ->toggleable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Dealer Name')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Benutzername')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make("additional_info")
                    ->label("Additional Information")
                    ->translateLabel()
                    ->view("filament.resources.ddas.additional-info")
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->wrap(),
                Tables\Columns\TextColumn::make("tags")
                    ->formatStateUsing(fn(Model $state) => $state->name_localized)
                    ->translateLabel()
                    ->toggleable()
                    ->badge(),
                Tables\Columns\IconColumn::make('approval')
                    ->label("Approved")
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('sigLocation.name')
                    ->label('Location')
                    ->translateLabel()
                    ->toggleable()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make("approval")
                    ->label("Approval")
                    ->translateLabel()
                    ->options(Approval::class),
                Tables\Filters\SelectFilter::make("tags")
                    ->relationship("tags", "name")
                    ->getOptionLabelFromRecordUsing(fn(DealerTag $record) => $record->name_localized),
                Tables\Filters\SelectFilter::make("sigLocation")
                    ->relationship("sigLocation", "name", fn(Builder $query) => $query->whereIn("id", Dealer::pluck("sig_location_id")))
                    ->getOptionLabelFromRecordUsing(fn(SigLocation $record) => $record->name_localized)
                    ->label("Location")
                    ->translateLabel(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->modalWidth("7xl"),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Approval::getBulkAction()
                        ->authorize("updateAny", Dealer::class),
                    Tables\Actions\BulkAction::make("sigLocation")
                        ->authorize("updateAny", Dealer::class)
                        ->label("Set Location")
                        ->translateLabel()
                        ->form([
                            Forms\Components\Select::make("sig_location_id")
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
            ->recordAction(Tables\Actions\EditAction::class)
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
            'index' => Pages\ListDealers::route('/'),
            'create' => Pages\CreateDealer::route('/create'),
            'view' => Pages\ViewDealer::route('/{record}'),
            'edit' => Pages\EditDealer::route('/{record}/edit'),
        ];
    }
}
