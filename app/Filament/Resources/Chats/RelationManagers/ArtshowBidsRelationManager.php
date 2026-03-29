<?php

namespace App\Filament\Resources\Chats\RelationManagers;

class ArtshowBidsRelationManager extends NestedUserRelationManager
{
    protected static string $nestedRelationship = "artshowBids";
    protected static string $relationManager = \App\Filament\Resources\Users\RelationManagers\ArtshowBidsRelationManager::class;
}
