<?php

namespace App\Filament\Resources\Chats\RelationManagers;


class RoleRelationManager extends NestedUserRelationManager
{
    protected static string $nestedRelationship = "roles";
    protected static string $relationManager = \App\Filament\Resources\Users\RelationManagers\RoleRelationManager::class;
}
