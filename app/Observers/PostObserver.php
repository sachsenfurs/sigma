<?php

namespace App\Observers;

use App\Models\Post\Post;

class PostObserver
{

    public function creating(Post $post): void {
        $post->user()->associate(auth()->user());
    }

    public function updated(Post $post): void {
        //
    }

    public function deleted(Post $post): void {
        //
    }

    public function restored(Post $post): void {
        //
    }

    public function forceDeleted(Post $post): void {
        //
    }
}
