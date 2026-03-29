<?php

namespace App\Filament\Resources\Chats\RelationManagers;


class SigHostsRelationManager extends NestedUserRelationManager
{
    protected static string $nestedRelationship = "sigHosts";
    protected static string $relationManager = \App\Filament\Resources\SigEvents\RelationManagers\SigHostsRelationManager::class;
}
