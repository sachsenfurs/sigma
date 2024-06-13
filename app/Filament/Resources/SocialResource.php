<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\Settings;
use App\Filament\Resources\SocialResource\Pages;
use App\Models\Info\Enums\ShowMode;
use App\Models\Info\Social;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SocialResource extends Resource
{
    protected static ?string $model = Social::class;

    protected static ?string $cluster = Settings::class;
    protected static ?int $navigationSort = 100;
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
    protected static ?string $navigationIcon = 'heroicon-o-share';

    public static function can(string $action, ?Model $record = null): bool {
        return auth()->user()->isAdmin();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('description')
                    ->label("Description")
                    ->translateLabel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('description_en')
                    ->label("Description (English)")
                    ->translateLabel()
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('link_name')
                    ->label("Link Name")
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('link')
                    ->label("Link")
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('link_name_en')
                    ->label("Link Name (English)")
                    ->translateLabel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('link_en')
                    ->label("Link (English)")
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('icon')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Grid::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label("Image")
                            ->translateLabel(),
                        Forms\Components\FileUpload::make('image_en')
                            ->label("Image (English)")
                            ->translateLabel(),
                    ]),
                Forms\Components\TextInput::make('order')
                    ->label("Order")
                    ->translateLabel()
                    ->integer()
                    ->default(0),
                Forms\Components\CheckboxList::make("show_on")
                    ->label("Show on..")
                    ->translateLabel()
                    ->options(ShowMode::class),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->label("Description")
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('link_name')
                    ->label("Link Name")
                    ->searchable(),
                Tables\Columns\TextColumn::make('link')
                    ->searchable(),
            ])
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSocials::route('/'),
            'create' => Pages\CreateSocial::route('/create'),
            'edit' => Pages\EditSocial::route('/{record}/edit'),
        ];
    }
}
