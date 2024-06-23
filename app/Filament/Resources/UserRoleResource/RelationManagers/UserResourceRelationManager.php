<?php

namespace App\Filament\Resources\UserRoleResource\RelationManagers;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class UserResourceRelationManager extends RelationManager
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

    public function form(Form $form): Form {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
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
                Tables\Actions\AttachAction::make()
                   ->label('Assign User')
                   ->translateLabel()
                   ->modalHeading(__('Assign User'))
                   ->modalSubmitActionLabel(__('Assign'))
                   ->attachAnother(false)
                   ->recordSelectSearchColumns(['id', 'name'])
                   ->recordTitle(fn($record) => $record->id . " - " . $record->name)
                   ->successNotificationTitle(__('User Assigned')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->url(fn(User $record) => UserResource::getUrl("edit", ['record' => $record])),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
