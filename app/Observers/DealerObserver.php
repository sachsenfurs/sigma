<?php

namespace App\Observers;

use App\Models\Ddas\Dealer;
use App\Notifications\Ddas\ProcessedDealerNotification;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class DealerObserver
{
    public function created(Dealer $dealer): void {
        $this->createUpdate($dealer);
    }

    public function updated(Dealer $dealer): void {
        if($dealer->isDirty("approval")) {
            $dealer->user->notify(new ProcessedDealerNotification($dealer));
        }
        $this->createUpdate($dealer);
    }

    public function createUpdate(Dealer $dealer): void {
        if($dealer->isDirty('icon_file')) {
            if(empty($dealer->icon_file)) {
                Storage::disk("public")->delete($dealer->getOriginal("icon_file"));
            } else {
                if($image = Image::read(Storage::disk('public')->get($dealer->icon_file))) {
                    if($image->height() > 500 OR $image->width() > 500)
                        $image->scaleDown(500);

                    $filename = md5($image->toJpeg()->toDataUri()).".jpeg";
                    if(Storage::disk('public')->put("$filename", $image->toJpeg())) {
                        Storage::disk('public')->delete($dealer->getOriginal("icon_file") ?? "");
                        Storage::disk('public')->delete($dealer->icon_file ?? "");
                        $dealer->icon_file = $filename;
                        $dealer->saveQuietly();
                    }
                }
            }
        }
    }


    public function deleted(Dealer $dealer): void {
        foreach($dealer->chats AS $chat) {
            $chat->subjectable()->dissociate();
            $chat->save();
        }
    }

}
