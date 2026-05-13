<?php

namespace App\Filament\Resources\ChatResource\RelationManagers;

use App\Filament\Resources\SigEventResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SigEventsRelationManager extends RelationManager
{

    protected static string $relationship = 'user';

    public static function getTitle(Model $ownerRecord, string $pageClass): string {
        return __("Assigned SIGs");
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string {
        return $ownerRecord->user->sigEvents()->count();
    }

    public function table(Table $table): Table {
        return SigEventResource::table($table)
           ->query($this->getOwnerRecord()->user->sigEvents())
           ->heading(__('Assigned SIGs'))
           ->recordAction('view');
    }
}
