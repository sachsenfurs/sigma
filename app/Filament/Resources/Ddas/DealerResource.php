<?php

namespace App\Filament\Resources\Ddas;

use App\Filament\Resources\Ddas\DealerResource\Pages;
use App\Filament\Resources\Ddas\DealerResource\RelationManagers;
use App\Models\Ddas\Dealer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class DealerResource extends Resource
{
    protected static ?string $model = Dealer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Dealer\'s Den';

    protected static ?int $navigationSort = 300;

    protected static array $spaces = [
        '0' => '0',
        '1' => '1',
        '2' => '2',
    ];

    protected static array $contactWays = [
        'telegram' => 'Telegram',
        'phone' => 'Phone',
        'email' => 'Email',
    ];

    public static function can(string $action, ?Model $record = null): bool
    {
        return auth()->user()->permissions()->contains('manage_dealers_den');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Dealers');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Benutzername')
                    ->relationship('user', 'name'),
                Forms\Components\Select::make('sig_location_id')
                    ->label('SIG Location')
                    ->relationship('sigLocation', 'name'),
                Forms\Components\TextInput::make('name')
                    ->label('Dealer Name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('info')
                    ->label('Dealer Info')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('info_en')
                    ->label('Dealer Info (EN)')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('gallery_link')
                    ->label('Galerie Link')
                    ->maxLength(255),
                Forms\Components\TextInput::make('contact')
                    ->label('Kontakt')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('icon_file')
                    ->label('Logo')
                    ->required()
                    ->disk('public')
                    ->image()
                    ->imageEditor()
                    ->maxFiles(1)
                    ->preserveFilenames()
                    ->maxSize(5120),
                Forms\Components\Toggle::make('approved')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Benutzername')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sigLocation.name')
                    ->label('SIG Location')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Dealer Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gallery_link')
                    ->label('Galerie Link')
                    ->toggleable()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('space')
                    ->label('Platzbedarf')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('contact_way')
                    ->label('Kontaktart')
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('contact')
                    ->label('Kontakt')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\ImageColumn::make('icon_file')
                    ->label('Logo')
                    ->toggleable(),
                Tables\Columns\IconColumn::make('approved')
                    ->boolean(),
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
            'index' => Pages\ListDealers::route('/'),
            'create' => Pages\CreateDealer::route('/create'),
            'edit' => Pages\EditDealer::route('/{record}/edit'),
        ];
    }
}
