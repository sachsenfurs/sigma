<?php

namespace App\Filament\Resources\Ddas;

use App\Enums\Approval;
use App\Filament\Actions\TranslateAction;
use App\Filament\Helper\FormHelper;
use App\Filament\Resources\Ddas\DealerResource\Pages;
use App\Models\Ddas\Dealer;
use App\Settings\DealerSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
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

        return Dealer::whereApproval(Approval::PENDING)->count() ?: null;
    }


    public static function form(Form $form): Form {
        return $form
            ->schema([
                Forms\Components\Fieldset::make("User Details")
                    ->translateLabel()
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('User')
                            ->searchable()
//                            ->preload()
                            ->getOptionLabelFromRecordUsing(FormHelper::formatUserWithRegId())
                            ->translateLabel()
                            ->relationship('user', 'name'),
                        Forms\Components\Radio::make('approval')
                            ->translateLabel()
                            ->options(Approval::class)
                            ->required(),
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
                                        fn($operation) => $operation != "view" ? TranslateAction::translateToPrimary('info_en', 'info') : null
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
                                        fn($operation) => $operation != "view" ? TranslateAction::translateToSecondary('info', 'info_en') : null
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->modalWidth("7xl"),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Approval::getBulkAction(),
                    Tables\Actions\BulkAction::make("sigLocation")
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
            ->recordUrl(null)
            ;
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
            'edit' => Pages\EditDealer::route('/{record}/edit'),
        ];
    }
}
