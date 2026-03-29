<?php

namespace App\Filament\Resources\UserRoles\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Actions\AttachAction;
use Filament\Actions\EditAction;
use Filament\Actions\DetachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachBulkAction;
use App\Filament\Helper\FormHelper;
use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class UserRelationManager extends RelationManager
{
    protected static string $relationship = 'users';
    protected static ?string $inverseRelationship = 'roles';
    protected static function getModelLabel(): ?string {
        return __("User");
    }

    protected static function getPluralModelLabel(): ?string {
        return __("Users");
    }

    protected function getTableHeading(): string|Htmlable|null {
        return __("Users");
    }

    public function form(Schema $schema): Schema {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table {
        return $table
            ->recordTitleAttribute('name')
            ->columns(
                UserResource::getTableColumns()
            )
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                   ->label('Assign User')
                   ->translateLabel()
                   ->modalHeading(__('Assign User'))
                   ->modalSubmitActionLabel(__('Assign'))
                   ->attachAnother(false)
                   ->recordSelectSearchColumns(['reg_id', 'name'])
                   ->recordTitle(FormHelper::formatUserWithRegId())
                   ->successNotificationTitle(__('User Assigned')),
            ])
            ->recordActions([
                EditAction::make()->url(fn(User $record) => UserResource::getUrl("edit", ['record' => $record])),
                DetachAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ]);
    }
}
