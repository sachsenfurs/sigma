<?php

namespace App\Filament\Resources\SigEventResource\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use App\Filament\Resources\SigHostResource;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class SigHostsRelationManager extends RelationManager
{
    protected static string $relationship = 'sigHosts';
    protected static string | \BackedEnum | null $icon = 'heroicon-o-users';

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

    public function form(Schema $schema): Schema {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table {
        $table = SigHostResource::table($table);
        return $table
            ->recordActions([
                EditAction::make("edit")
                    ->schema(fn($form) => SigHostResource::form($form))
            ])
            ->recordUrl(fn(Model $record) =>
                SigHostResource::getUrl(
                    Gate::check("update", $record) ? "edit" : "view",
                    ["record" => $record])
                )
            ->headerActions([
                CreateAction::make()
                    ->schema(fn($form) => SigHostResource::form($form))
                    ->fillForm([
                        'reg_id' => $this->ownerRecord->reg_id,
                        'name' => $this->ownerRecord->name,
                    ]),
            ]);
    }
}
