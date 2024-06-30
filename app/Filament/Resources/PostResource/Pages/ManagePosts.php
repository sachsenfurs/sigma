<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use App\Models\Post\Post;
use App\Models\Post\PostChannel;
use Filament\Actions;
use Filament\Forms\Get;
use Filament\Resources\Pages\ManageRecords;

class ManagePosts extends ManageRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array {
        return [
            Actions\CreateAction::make()
                ->label("New Post")
                ->translateLabel()
                ->after(function($livewire, Post $record) {
                    $channels = PostChannel::find($livewire->mountedActionsData[0] ?? []);
                    foreach($channels AS $channel) {
                        $channel->sendMessage($record);
                    }
                })
            ,
        ];
    }




}
