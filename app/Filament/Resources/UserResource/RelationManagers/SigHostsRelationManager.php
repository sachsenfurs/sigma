<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Resources\SigHostResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SigHostsRelationManager extends RelationManager
{
    protected static string $relationship = 'sigHosts';
    protected static ?string $icon = 'heroicon-o-users';


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
            ->recordUrl(fn(Model $record) => SigHostResource::getUrl("edit", ["record" => $record]))
        ;
    }
}
