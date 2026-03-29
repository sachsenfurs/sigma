<?php

namespace App\Filament\Resources\Chats\RelationManagers;


class ArtistsRelationManager extends NestedUserRelationManager
{
    protected static string $nestedRelationship = "artists";
    protected static string $relationManager = \App\Filament\Resources\Users\RelationManagers\ArtistsRelationManager::class;
}
