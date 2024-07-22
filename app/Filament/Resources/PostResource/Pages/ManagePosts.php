<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use App\Models\Post\Post;
use App\Models\Post\PostChannel;
use Filament\Actions;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;

class ManagePosts extends ManageRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array {
        return [
            Actions\CreateAction::make()
                ->label("New Post")
                ->translateLabel()
                ->after(function($livewire, $data) {
                    $channels = PostChannel::find($data['channels'] ?? []);
                    unset($data['channels']);

                    $post = Post::create($data);
                    foreach($channels AS $channel) {
                        $channel->sendMessage($post);
                    }
                })
                ->extraModalFooterActions([
                    Actions\Action::make("test")
                        ->makeModalSubmitAction("test", ['test' => true])
                ])
                ->action(function(array $data, $action, array $arguments) {
                    if($arguments['test'] ?? false) {
                        $channels = PostChannel::find($data['channels'] ?? []);
                        unset($data['channels']);

                        $post = new Post($data);
                        foreach($channels AS $channel) {
                            $channel->sendTestMessage($post);
                        }
                        Notification::make("sent")
                            ->title(__("Test Message sent!"))
                            ->success()
                            ->send();
                        $action->halt();
                    }
                })
            ,
        ];
    }


//    public function validate($rules = null, $messages = [], $attributes = []): array {
//        dd($attributes);
//    }

}
