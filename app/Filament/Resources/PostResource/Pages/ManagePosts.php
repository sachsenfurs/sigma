<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
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
                ->mutateFormDataUsing(function($data) {
                    $channels = PostChannel::find($data['channels'] ?? []);
                    foreach($channels AS $channel) {
//                        $channel->sendMessage(... post);
                    }
                    unset($data['channels']);
                    return $data;
                })
            ,
        ];
    }




}
