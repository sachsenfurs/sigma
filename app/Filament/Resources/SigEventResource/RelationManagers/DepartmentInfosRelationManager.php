<?php

namespace App\Filament\Resources\SigEventResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class DepartmentInfosRelationManager extends RelationManager
{
    protected static string $relationship = 'departmentInfos';
    protected static ?string $icon = 'heroicon-o-user-group';

    public static function getPluralModelLabel(): ?string {
        return __("Assigned Departments");
    }

    public static function getModelLabel(): ?string {
        return __("Assign Department");
    }
    public static function getTitle(Model $ownerRecord, string $pageClass): string {
        return __("Assigned Departments");
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool {
        // Filament needs to know if the user can view the relation manager for the given record.
        return auth()->user()->can("view", $ownerRecord);
    }

    protected function can(string $action, ?Model $record = null): bool {
        // Filament needs to know if the user can perform the given action on the relation manager.
        return auth()->user()->can("viewAny", $record);
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string {
        return $ownerRecord->departmentInfos->count() ?: null;
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user_role_id')
            ->columns([
                Tables\Columns\TextColumn::make('userRole.name_localized')
                    ->label('User Role')
                    ->translateLabel()
                    ->sortable(),
                Tables\Columns\TextColumn::make('additional_info')
                    ->label('Requirements to Department')
                    ->translateLabel()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Assign Department')
                    ->translateLabel()
                    ->modalHeading(__('Assign Department'))
                    ->modalSubmitActionLabel(__('Assign'))
                    ->createAnother(false)
                    ->successNotificationTitle(__('Department Assigned'))
                    ->form([
                        Forms\Components\Select::make('user_role_id')
                            ->label('Department')
                            ->translateLabel()
                            ->relationship('userRole', 'name')
                            ->getOptionLabelFromRecordUsing(fn($record) => $record->name_localized)
                            ->preload()
                            ->searchable()
                            ->required(),
                        Forms\Components\Textarea::make('additional_info')
                            ->label('Requirements to Department')
                            ->translateLabel()
                            ->rows(4)
                            ->required()
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit Requirements')
                    ->translateLabel()
                    ->modalHeading(__('Edit Requirements'))
                    ->successNotificationTitle(__('Requirements Edited'))
                    ->form([
                        Forms\Components\Select::make('user_role_id')
                            ->label('Department')
                            ->translateLabel()
                            ->relationship('userRole', 'name')
                            ->getOptionLabelFromRecordUsing(fn($record) => $record->name_localized)
                            ->preload()
                            ->searchable()
                            ->required(),
                        Forms\Components\Textarea::make('additional_info')
                            ->label('Requirements to Department')
                            ->translateLabel()
                            ->rows(4)
                            ->required()
                    ]),
                Tables\Actions\DeleteAction::make()
                    ->label('Remove Department')
                    ->translateLabel()
                    ->modalHeading(__('Remove Department'))
                    ->successNotificationTitle(__('Department Removed')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
