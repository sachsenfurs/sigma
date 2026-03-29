<?php

namespace App\Filament\Resources\Chats\RelationManagers;

class DealersRelationManager extends NestedUserRelationManager
{
    protected static string $nestedRelationship = "dealers";
    protected static string $relationManager = \App\Filament\Resources\Users\RelationManagers\DealersRelationManager::class;
}
