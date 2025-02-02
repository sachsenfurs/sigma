<?php

namespace App\Filament\Resources\ChatResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class NestedUserRelationManager extends RelationManager
{
    protected static string $nestedRelationship = "";
    protected static string $relationManager;
    protected static string $relationship = 'user';

    private function makeRelationManager() {
        $rm = new static::$relationManager($this);
        $rm->ownerRecord = $this->getOwnerRecord();
        return $rm;
    }

    public static function getIcon(Model $ownerRecord, string $pageClass): ?string {
        return static::$relationManager::getIcon($ownerRecord, $pageClass);
    }

    public function getRelationship(): Relation|Builder {
        return $this->ownerRecord->user->{static::$nestedRelationship}();
    }

    public function getOwnerRecord(): Model {
        return $this->ownerRecord->user;
    }

    public static function getTitle(?Model $ownerRecord, string $pageClass): string {
        return $ownerRecord->{static::$relationship} ? static::$relationManager::getTitle($ownerRecord->{static::$relationship}, $pageClass) : "";
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string {
        return static::$relationManager::getBadge($ownerRecord->{static::$relationship}, $pageClass);
    }

    public function form(Form $form): Form {
        return $this->makeRelationManager()->form($form);
    }

    public function table(Table $table): Table {
        return $this->makeRelationManager()->table($table);
    }
}
