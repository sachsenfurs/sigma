<?php

namespace App\Observers;

use App\Models\Post\PostChannel;

class PostChannelObserver
{
    public function deleted(PostChannel $postChannel): void {
        // deleting the polymorphic relationships which can't be handled by the DB
        $postChannel->notificationRoutes()->delete();
        $postChannel->notifications()->delete();
        $postChannel->reminders()->delete();
    }
}
