<?php

namespace App\Services\PostChannels;

use App\Integrations\Barq\Operations\CreateEventAnnouncement;
use App\Jobs\BarqCreateEventAnnouncement;
use App\Jobs\BarqDeleteEventAnnouncement;
use App\Models\Post\Post;
use App\Models\Post\PostChannel;
use App\Models\Post\PostChannelMessage;
use Illuminate\Support\Str;

class BarqPostChannel implements PostChannelImplementation
{
    public function send(PostChannel $channel, Post $post): void {
        BarqCreateEventAnnouncement::dispatch($post->id, $channel->id);
    }

    public function sendTest(PostChannel $channel, Post $post): void {
        if($channel->test_channel_identifier) {
            BarqCreateEventAnnouncement::dispatch($post->id, $channel->id, true);
        }
    }

    public function update(PostChannelMessage $message): void {
        // update not supported
    }

    public function delete(PostChannelMessage $message): void {
        BarqDeleteEventAnnouncement::dispatch($message->message_id);
    }
}
