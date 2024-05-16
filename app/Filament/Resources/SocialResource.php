<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SocialResource\Pages;
use App\Models\Info\Enum\ShowMode;
use App\Models\Info\Social;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SocialResource extends Resource
{
    protected static ?string $model = Social::class;

    protected static ?string $navigationGroup = "System";

    protected static ?int $navigationSort = 400;
    protected static ?string $navigationIcon = 'heroicon-o-share';

    public static function can(string $action, ?Model $record = null): bool {
        return auth()->user()->isAdmin();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('description_en')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('link_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('link')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('link_name_en')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('link_en')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('icon')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Textarea::make('qr')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('qr_en')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('order')
                    ->integer()
                    ->default(0),
                Forms\Components\CheckboxList::make("show_on")
                    ->options(ShowMode::class),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('link_name')
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
