<?php

namespace App\Filament\Resources\SigEvents\RelationManagers;

use BackedEnum;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class DepartmentInfosRelationManager extends RelationManager
{
    protected static string $relationship = 'departmentInfos';
    protected static string | BackedEnum | null $icon = 'heroicon-o-user-group';

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

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string {
        return $ownerRecord->departmentInfos->count() ?: null;
    }

    public function isReadOnly(): bool {
        return false;
    }

    public function table(Table $table): Table {
        return $table
            ->recordTitleAttribute('user_role_id')
            ->columns([
                TextColumn::make('userRole')
                    ->label('User Role')
                    ->formatStateUsing(fn($state) => $state->name_localized)
                    ->badge()
                    ->translateLabel()
                    ->sortable(),
                TextColumn::make('additional_info')
                    ->label('Requirements to Department')
                    ->translateLabel()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Assign Department')
                    ->translateLabel()
                    ->modalHeading(__('Assign Department'))
                    ->modalSubmitActionLabel(__('Assign'))
                    ->createAnother(false)
                    ->successNotificationTitle(__('Department Assigned'))
                    ->schema([
                        Select::make('user_role_id')
                            ->label('Department')
                            ->translateLabel()
                            ->relationship('userRole', 'name')
                            ->getOptionLabelFromRecordUsing(fn($record) => $record->name_localized)
                            ->preload()
                            ->searchable()
                            ->required(),
                        Textarea::make('additional_info')
                            ->label('Requirements to Department')
                            ->translateLabel()
                            ->rows(4)
                            ->required()
                    ]),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Edit Requirements')
                    ->translateLabel()
                    ->modalHeading(__('Edit Requirements'))
                    ->successNotificationTitle(__('Requirements Edited'))
                    ->schema([
                        Select::make('user_role_id')
                            ->label('Department')
                            ->translateLabel()
                            ->relationship('userRole', 'name')
                            ->getOptionLabelFromRecordUsing(fn($record) => $record->name_localized)
                            ->preload()
                            ->searchable()
                            ->required(),
                        Textarea::make('additional_info')
                            ->label('Requirements to Department')
                            ->translateLabel()
                            ->rows(4)
                            ->required()
                    ]),
                DeleteAction::make()
                    ->label('Remove Department')
                    ->translateLabel()
                    ->modalHeading(__('Remove Department'))
                    ->successNotificationTitle(__('Department Removed')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
