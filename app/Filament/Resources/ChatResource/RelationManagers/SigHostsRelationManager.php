<?php

namespace App\Filament\Resources\ChatResource\RelationManagers;

class SigHostsRelationManager extends NestedUserRelationManager
{
    protected static string $nestedRelationship = "sigHosts";
    protected static string $relationManager = \App\Filament\Resources\SigEventResource\RelationManagers\SigHostsRelationManager::class;
}
