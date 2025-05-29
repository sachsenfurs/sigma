<?php

namespace App\Filament\Resources\SigEventResource\RelationManagers;

use App\Filament\Resources\SigHostResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class SigHostsRelationManager extends RelationManager
{
    protected static string $relationship = 'sigHosts';
    protected static ?string $icon = 'heroicon-o-users';

    public static function getModelLabel(): ?string {
        return __("Host");
    }

    public function isReadOnly(): bool {
        return true;
    }

    public static function getPluralModelLabel(): ?string {
        return __("Hosts");
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string {
        return __("SIG Hosts");
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string {
        return $ownerRecord->sigHosts()->count();
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
        $table = SigHostResource::table($table);
        return $table
            ->actions([
                EditAction::make("edit")
                    ->form(fn($form) => SigHostResource::form($form))
            ])
            ->recordUrl(fn(Model $record) =>
                SigHostResource::getUrl(
                    Gate::check("update", $record) ? "edit" : "view",
                    ["record" => $record])
                )
            ->headerActions([
                CreateAction::make()
                    ->form(fn($form) => SigHostResource::form($form))
                    ->fillForm([
                        'reg_id' => $this->ownerRecord->reg_id,
                        'name' => $this->ownerRecord->name,
                    ]),
            ]);
    }
}
