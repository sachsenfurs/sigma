<?php

namespace App\Filament\Resources\ChatResource\RelationManagers;

class DealersRelationManager extends NestedUserRelationManager
{
    protected static string $nestedRelationship = "dealers";
    protected static string $relationManager = \App\Filament\Resources\UserResource\RelationManagers\DealersRelationManager::class;
}
