<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Resources\Ddas\DealerResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class DealersRelationManager extends RelationManager
{
    protected static string $relationship = 'dealers';
    protected static ?string $icon = 'heroicon-o-shopping-cart';

    public static function getModelLabel(): ?string {
        return __("Dealer");
    }

    public static function getPluralModelLabel(): ?string {
        return __("Dealers");
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string {
        return __("Dealers");
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string {
        return $ownerRecord->dealers()->count();
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
        $table = DealerResource::table($table);
        $table->getColumn("user.name")->hidden();
        return $table
            ->recordUrl(fn(Model $record) => DealerResource::getUrl("edit", ["record" => $record]))
            ->actions([])
            ->headerActions([
                CreateAction::make()
                    ->form(fn($form) => DealerResource::form($form))
                    ->fillForm([
                        'user_id' => $this->ownerRecord->id,
                    ]),
            ]);
    }
}
