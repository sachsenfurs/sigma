<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Filament\Traits\HasActiveIcon;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UserResource extends Resource
{
    use HasActiveIcon;
    protected static ?string $model = User::class;

    protected static ?string $navigationGroup = "System";

    protected static ?int $navigationSort = 1100;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function getLabel(): ?string {
        return __('User');
    }

    public static function getPluralLabel(): ?string {
        return __('Users');
    }

    public static function form(Form $form): Form {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->helperText(__("This field is overwritten with every user logon"))
                    ->translateLabel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('reg_id')
                    ->helperText(__("This field is overwritten with every user logon"))
                    ->translateLabel()
                    ->unique(ignoreRecord: true)
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table {
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

    public static function getTableColumns(): array {
        return [
            Tables\Columns\TextColumn::make('reg_id')
                ->numeric()
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('name')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('roles')
                ->label('User Roles')
                ->translateLabel()
                ->formatStateUsing(fn($state) => $state->name_localized)
                ->badge(),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make("favorites_count")
                ->counts("favorites")
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    public static function getRelations(): array {
        return [
            RelationManagers\RoleRelationManager::class,
            RelationManagers\ArtistsRelationManager::class,
            RelationManagers\DealersRelationManager::class,
            SigEventResource\RelationManagers\SigHostsRelationManager::class,
            RelationManagers\FavoritesRelationManager::class,
            RelationManagers\ArtshowBidsRelationManager::class,
            RelationManagers\NotificationsRelationManager::class,
        ];
    }

    public static function getPages(): array {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    private static function getRoleFilter(): Tables\Filters\SelectFilter {
        return Tables\Filters\SelectFilter::make('roles')
            ->label('User Role')
            ->translateLabel()
            ->searchable()
            ->preload()
            ->getOptionLabelFromRecordUsing(fn($record) => $record->name_localized)
            ->relationship('roles', 'name', fn (Builder $query) => $query->orderBy('name'));
    }

}
