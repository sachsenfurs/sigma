<?php

namespace App\Filament\Resources\ChatResource\RelationManagers;

class RoleRelationManager extends NestedUserRelationManager
{
    protected static string $nestedRelationship = "roles";
    protected static string $relationManager = \App\Filament\Resources\UserResource\RelationManagers\RoleRelationManager::class;
}
