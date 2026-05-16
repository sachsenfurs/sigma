<?php

namespace App\Jobs;

use App\Integrations\Barq\Operations\CreateEventAnnouncement;
use App\Models\Post\Post;
use App\Settings\AppSettings;
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
        public readonly int $postId
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void {
        if($post = Post::find($this->postId)) {

            $text = $post->text;
            if($post->text_en)
                $text = $post->text_en ."\r\n\r\n".$post->text;

            app()->makeWith(CreateEventAnnouncement::class, [
                'uuid' => app(AppSettings::class)->barq_event_uuid,
                'title' => Str::limit($text, 30),
                'body' => $text,
            ])->execute();
        }
    }
}
