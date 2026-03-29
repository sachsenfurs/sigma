<?php

namespace App\Filament\Resources\Posts\Pages;

use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use App\Filament\Resources\Posts\PostResource;
use App\Models\Post\Post;
use App\Models\Post\PostChannel;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ManagePosts extends ManageRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array {
        return [
            CreateAction::make()
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
                    Action::make("test")
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
                ->mutateDataUsing(function (array $data): array {
                    // resize image
                   if(isset($data['image'])) {
                       $path    = Storage::disk("public")->path($data['image']);
                       $image   = Image::read($path);
                       if($image->height() > 800 OR $image->width() > 800) {
                           $image->scaleDown(800);
                           $image->save();
                       }
                   }
                    return $data;
                })
            ,
        ];
    }

}
