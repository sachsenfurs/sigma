<?php

namespace App\Services\PostChannels;

use App\Models\Post\Post;
use App\Models\Post\PostChannel;
use App\Models\Post\PostChannelMessage;

interface PostChannelImplementation
{
    public function send(PostChannel $channel, Post $post): void;

    public function sendTest(PostChannel $channel, Post $post): void;

    public function update(PostChannelMessage $message): void;

    public function delete(PostChannelMessage $message): void;
}
