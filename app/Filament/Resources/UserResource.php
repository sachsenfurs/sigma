<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationGroup = "System";

    protected static ?int $navigationSort = 1100;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function can(string $action, ?Model $record = null): bool
    {
        return auth()->user()->permissions()->contains('manage_users');
    }

    public static function getLabel(): ?string
    {
        return __('User');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Users');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                self::getNameField(),
                self::getRegIdField(),
                self::getEmailField(),
                self::getPasswordField(),
                self::getTelegramUserIdField(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::getTableColumns())
            ->filters([
                self::getRoleFilter(),
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

    public static function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('reg_id')
                ->numeric()
                ->sortable(),
            Tables\Columns\TextColumn::make('name')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('email')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('roles.title')
                ->label('User Roles')
                ->translateLabel()
                ->badge(),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\RoleRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    private static function getRoleFilter(): Tables\Filters\SelectFilter
    {
        return Tables\Filters\SelectFilter::make('roles')
            ->label('User Role')
            ->translateLabel()
            ->relationship('roles', 'title', fn (Builder $query) => $query->orderBy('title'));
    }

    private static function getNameField(): Forms\Components\Component
    {
        return Forms\Components\TextInput::make('name')
            ->required()
            ->maxLength(255);
    }
    private static function getRegIdField(): Forms\Components\Component
    {
        return Forms\Components\TextInput::make('reg_id')
            ->numeric();
    }

    private static function getEmailField(): Forms\Components\Component
    {
        return Forms\Components\TextInput::make('email')
            ->email()
            ->maxLength(255);
    }

    private static function getPasswordField(): Forms\Components\Component
    {
        return Forms\Components\TextInput::make('password')
            ->password()
            ->maxLength(255);
    }

    private static function getTelegramUserIdField()
    {
        return Forms\Components\TextInput::make('telegram_user_id')
            ->tel()
            ->maxLength(255);
    }
}
