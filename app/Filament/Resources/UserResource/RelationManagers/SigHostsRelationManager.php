<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Resources\SigHostResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SigHostsRelationManager extends RelationManager
{
    protected static string $relationship = 'sigHosts';

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
//        $table->getColumn("name")->hidden();
        return $table
            ->recordUrl(fn(Model $record) => SigHostResource::getUrl("edit", ["record" => $record]))
            ->actions([]);
    }
}
