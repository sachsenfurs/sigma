<?php

namespace App\Jobs;

use App\Integrations\Barq\Operations\CreateEventAnnouncement;
use App\Models\Post\Post;
use App\Models\Post\PostChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Str;


class BarqCreateEventAnnouncement implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public readonly int $postId,
        public readonly int $postChannelId,
        public readonly bool $test = false,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void {
        $post = Post::find($this->postId);
        $channel = PostChannel::find($this->postChannelId);

        if(!$post || !$channel) {
            return;
        }

        $channel_id = $this->test ? $channel->test_channel_identifier : $channel->channel_identifier;
        $text       = $post->text;

        if($post->text_en)
            $text = $post->text_en ."\r\n\r\n".$post->text;

        $messageId = app()->makeWith(CreateEventAnnouncement::class, [
            'uuid' => $channel_id,
            'title' => Str::of($text)->squish()->limit(30),
            'body' => $text,
        ])->execute();

        if($messageId && !$post->messages()->where('post_channel_id', $channel->id)->exists()) {
            $post->channels()->attach([
                [
                    'post_channel_id' => $channel->id,
                    'message_id' => $messageId,
                ]
            ]);
        }
    }
}
