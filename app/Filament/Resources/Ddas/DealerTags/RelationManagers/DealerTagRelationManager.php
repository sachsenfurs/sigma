<?php

namespace App\Filament\Resources\Ddas\DealerTags\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\Ddas\Dealers\DealerResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;


class DealerTagRelationManager extends RelationManager
{
    protected static string $relationship = 'dealers';

    protected static ?string $title = "Tagged Dealers";

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return DealerResource::table($table)
            ->searchable(false)
            ->filters([]);
    }
}
