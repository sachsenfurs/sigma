<?php

namespace App\Jobs;

use App\Integrations\Barq\Operations\DeleteEventAnnouncement;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class BarqDeleteEventAnnouncement implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public readonly int $postChannelMessageMessageId
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void {
        app()->makeWith(DeleteEventAnnouncement::class, [
            'id' => $this->postChannelMessageMessageId,
        ])->execute();
    }
}
