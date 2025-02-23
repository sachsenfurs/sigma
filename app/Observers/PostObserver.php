<?php

namespace App\Observers;

use App\Models\Post\Post;
use Illuminate\Support\Facades\Storage;

class PostObserver
{

    public function creating(Post $post): void {
        $post->user()->associate(auth()->user());
    }

    public function updated(Post $post): void {
        $post->messages->each->updateMessage();
    }

    public function deleted(Post $post): void {
        foreach($post->channels->pluck("postChannelMessage") AS $message) {
            $message->delete();
        }
        if($post->image AND Storage::disk("public")->exists($post->image))
            Storage::disk("public")->delete($post->image);
    }

    public function restored(Post $post): void {
        //
    }

    public function forceDeleted(Post $post): void {
        //
    }
}
