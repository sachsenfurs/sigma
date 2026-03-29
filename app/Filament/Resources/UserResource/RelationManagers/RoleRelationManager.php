<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\AttachAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Width;
use Filament\Actions\DetachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachBulkAction;
use App\Models\UserRole;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class RoleRelationManager extends RelationManager
{
    protected static string $relationship = 'roles';
    protected static string | \BackedEnum | null $icon = 'heroicon-o-user-group';


    public static function getTitle(Model $ownerRecord, string $pageClass): string {
        return __("User Roles");
    }

    public static function getModelLabel(): ?string {
        return __("User Role");
    }

    public static function getPluralModelLabel(): ?string {
        return __("User Roles");
    }

    protected function getTableHeading(): string|Htmlable|null {
        return __('User Roles');
    }
    public static function getBadge(Model $ownerRecord, string $pageClass): ?string {
        return $ownerRecord->roles()->count();
    }


    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool {
        // Filament needs to know if the user can view the relation manager for the given record.
        return auth()->user()->can('view', $ownerRecord);
    }

    protected function can(string $action, ?Model $record = null): bool {
        // Filament needs to know if the user can perform the given action on the relation manager.
        return auth()->user()->can('viewAny', UserRole::class);
    }

    public function table(Table $table): Table {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name_localized')
                    ->label(__("Name")),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Assign Role')
                    ->translateLabel()
                    ->schema(fn (AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->options(UserRole::whereNotIn("id", auth()->user()->roles->pluck("id"))->get()->pluck("name_localized", "id")), // yes, thats the only way i found to apply the localization correctly >.>
                    ])
                    ->modalHeading(__('Assign Role'))
                    ->modalSubmitActionLabel(__('Assign'))
                    ->attachAnother(false)
                    ->successNotificationTitle(__('Role Assigned'))
                    ->preloadRecordSelect(),
            ])
            ->recordActions([
                ViewAction::make('viewPermissions')
                    ->label('Permissions')
                    ->translateLabel()
                    ->icon('heroicon-s-key')
                    ->modalWidth(Width::ExtraLarge)
                    ->modalHeading(__('Permissions'))
                    ->schema([
                        KeyValueEntry::make('permissions')
                            ->keyLabel(__('Permission'))
                            ->valueLabel(__('Level'))
                            ->state(function ($record) {
                                return $record->permissions->mapWithKeys(function ($userRolePermission) {
                                    return [$userRolePermission->permission->name() => $userRolePermission->level->name()];
                                });
                            }),
                    ]),
                DetachAction::make()
                    ->label('Remove Role')
                    ->translateLabel()
                    ->modalHeading(__('Remove Role'))
                    ->successNotificationTitle(__('Role Removed')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ]);
    }
}
