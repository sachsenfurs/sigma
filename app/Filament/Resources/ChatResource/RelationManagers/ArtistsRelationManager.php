<?php

namespace App\Filament\Resources\ChatResource\RelationManagers;


class ArtistsRelationManager extends NestedUserRelationManager
{
    protected static string $nestedRelationship = "artists";
    protected static string $relationManager = \App\Filament\Resources\UserResource\RelationManagers\ArtistsRelationManager::class;
}
