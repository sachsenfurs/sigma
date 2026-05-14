<?php

namespace App\Notifications\Ddas;

use App\Models\Ddas\Dealer;
use App\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubmittedDealerApplicationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public static bool $userSetting = false;

    public function __construct(
        public Dealer $dealer
    ) {}

    // enforced channels
    protected function getVia(): array {
        return ['mail', 'telegram'];
    }

    protected function getSubject(): ?string {
        return __(__("We have received your dealer application!"));
    }

    protected function getLines(): array {
        return [
            __("Details of your application:"),
            __("Dealer Name") . ": " . $this->dealer->name,
            __("Dealer Information (What do you offer, ..?)") . ": " . $this->dealer->info,
            __("Additional Information") . ": " . $this->dealer->additional_info,
            "",
            __("Please give us some time to process your application. We will notify you as soon as your status changes.")
        ];
    }
}
