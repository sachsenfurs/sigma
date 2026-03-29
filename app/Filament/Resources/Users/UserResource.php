<?php

namespace App\Filament\Resources\Users;

use BackedEnum;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\Users\RelationManagers\RoleRelationManager;
use App\Filament\Resources\Users\RelationManagers\ArtistsRelationManager;
use App\Filament\Resources\Users\RelationManagers\DealersRelationManager;
use App\Filament\Resources\SigEvents\RelationManagers\SigHostsRelationManager;
use App\Filament\Resources\Users\RelationManagers\FavoritesRelationManager;
use App\Filament\Resources\Users\RelationManagers\ArtshowBidsRelationManager;
use App\Filament\Resources\Users\RelationManagers\NotificationsRelationManager;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Traits\HasActiveIcon;
use App\Models\User;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class UserResource extends Resource
{
    use HasActiveIcon;
    protected static ?string $model = User::class;

    protected static string | UnitEnum | null $navigationGroup = "System";

    protected static ?int $navigationSort = 1100;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedUserGroup;

    public static function getLabel(): ?string {
        return __('User');
    }

    public static function getPluralLabel(): ?string {
        return __('Users');
    }

    public static function form(Schema $schema): Schema {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->helperText(__("This field is overwritten with every user logon"))
                    ->translateLabel()
                    ->maxLength(255),
                TextInput::make('reg_id')
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
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getTableColumns(): array {
        return [
            TextColumn::make('reg_id')
                ->numeric()
                ->searchable()
                ->sortable(),
            TextColumn::make('name')
                ->sortable()
                ->searchable(),
            TextColumn::make('roles')
                ->label('User Roles')
                ->translateLabel()
                ->formatStateUsing(fn($state) => $state->name_localized)
                ->badge(),
            TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make("favorites_count")
                ->counts("favorites")
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    public static function getRelations(): array {
        return [
            RoleRelationManager::class,
            ArtistsRelationManager::class,
            DealersRelationManager::class,
            SigHostsRelationManager::class,
            FavoritesRelationManager::class,
            ArtshowBidsRelationManager::class,
            NotificationsRelationManager::class,
        ];
    }

    public static function getPages(): array {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }

    private static function getRoleFilter(): SelectFilter {
        return SelectFilter::make('roles')
            ->label('User Role')
            ->translateLabel()
            ->searchable()
            ->preload()
            ->getOptionLabelFromRecordUsing(fn($record) => $record->name_localized)
            ->relationship('roles', 'name', fn (Builder $query) => $query->orderBy('name'));
    }

}
