<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Infolists\Components\KeyValueEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class RoleRelationManager extends RelationManager
{
    protected static string $relationship = 'roles';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool {
        // Filament needs to know if the user can view the relation manager for the given record.
        return auth()->user()->can('manage_users');
    }

    protected function can(string $action, ?Model $record = null): bool {
        // Filament needs to know if the user can perform the given action on the relation manager.
        return auth()->user()->can('manage_users');
    }

    protected function getTableHeading(): string|Htmlable|null {
        return __('User Roles');
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string {
        return $ownerRecord->roles()->count();
    }

    public function table(Table $table): Table {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->label('Assign Role')
                    ->translateLabel()
                    ->modalHeading(__('Assign Role'))
                    ->modalSubmitActionLabel(__('Assign'))
                    ->attachAnother(false)
                    ->successNotificationTitle(__('Role Assigned'))
                    ->preloadRecordSelect(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make('viewPermissions')
                    ->label('Permissions')
                    ->translateLabel()
                    ->icon('heroicon-s-key')
                    ->modalWidth(MaxWidth::ExtraLarge)
                    ->modalHeading(__('Permissions'))
                    ->infolist([
                        KeyValueEntry::make('permissions')
                            ->keyLabel(__('Permission'))
                            ->valueLabel(__('Description'))
                            ->state(function ($record) {
                                return $record->permissions->mapWithKeys(function ($permission) {
                                    return [$permission->name => $permission->friendly_name];
                                });
                            }),
                    ]),
                Tables\Actions\DetachAction::make()
                    ->label('Remove Role')
                    ->translateLabel()
                    ->modalHeading(__('Remove Role'))
                    ->successNotificationTitle(__('Role Removed')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
