<?php

namespace App\Filament\Resources\ChatResource\RelationManagers;

class ArtshowBidsRelationManager extends NestedUserRelationManager
{
    protected static string $nestedRelationship = "artshowBids";
    protected static string $relationManager = \App\Filament\Resources\UserResource\RelationManagers\ArtshowBidsRelationManager::class;
}
