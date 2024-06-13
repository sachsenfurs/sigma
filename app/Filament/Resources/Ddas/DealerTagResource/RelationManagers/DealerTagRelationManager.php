<?php

namespace App\Filament\Resources\Ddas\DealerTagResource\RelationManagers;

use App\Filament\Resources\Ddas\DealerResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;


class DealerTagRelationManager extends RelationManager
{
    protected static string $relationship = 'dealers';

    protected static ?string $title = "Tagged Dealers";

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
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
