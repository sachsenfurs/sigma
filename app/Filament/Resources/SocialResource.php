<?php

namespace App\Filament\Resources;

use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\CheckboxList;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\SocialResource\Pages\ListSocials;
use App\Filament\Resources\SocialResource\Pages\CreateSocial;
use App\Filament\Resources\SocialResource\Pages\EditSocial;
use App\Filament\Clusters\Settings;
use App\Filament\Resources\SocialResource\Pages;
use App\Filament\Traits\HasActiveIcon;
use App\Models\Info\Enums\ShowMode;
use App\Models\Info\Social;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SocialResource extends Resource
{
    use HasActiveIcon;
    protected static ?string $model = Social::class;
    protected static ?string $cluster = Settings::class;
    protected static ?int $navigationSort = 1900;
    protected static ?\Filament\Pages\Enums\SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-share';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make()
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('description')
                            ->label("Description")
                            ->translateLabel()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('description_en')
                            ->label("Description (English)")
                            ->translateLabel()
                            ->maxLength(255)
                            ->default(null),
                    ]),
                Fieldset::make()
                    ->columnSpanFull()
                    ->schema([
                            TextInput::make('link_name')
                                ->label("Link Name")
                                ->required()
                                ->maxLength(255),
                            TextInput::make('link')
                                ->label("Link")
                                ->maxLength(255)
                                ->default(null),
                            TextInput::make('link_name_en')
                                ->label("Link Name (English)")
                                ->translateLabel()
                                ->required()
                                ->maxLength(255),
                            TextInput::make('link_en')
                                ->label("Link (English)")
                                ->maxLength(255)
                                ->default(null),
                ]),
                Fieldset::make()
                     ->columnSpanFull()
                     ->schema([
                           TextInput::make('icon')
                               ->maxLength(255)
                               ->default(null),
                            Grid::make()
                                ->columns(2)
                                ->columnSpanFull()
                                ->schema([
                                   FileUpload::make('image')
                                       ->label("Image")
                                       ->translateLabel(),
                                   FileUpload::make('image_en')
                                       ->label("Image (English)")
                                       ->translateLabel(),
                           ]),
                ]),
                TextInput::make('order')
                    ->label("Order")
                    ->translateLabel()
                    ->integer()
                    ->default(0),
                CheckboxList::make("show_on")
                    ->label("Show on..")
                    ->translateLabel()
                    ->options(ShowMode::class),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('description')
                    ->label("Description")
                    ->translateLabel()
                    ->searchable(),
                TextColumn::make('link_name')
                    ->label("Link Name")
                    ->searchable(),
                TextColumn::make('link')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->after(fn() => Social::clearCache()),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSocials::route('/'),
            'create' => CreateSocial::route('/create'),
            'edit' => EditSocial::route('/{record}/edit'),
        ];
    }
}
