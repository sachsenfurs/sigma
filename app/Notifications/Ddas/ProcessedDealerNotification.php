<?php

namespace App\Notifications\Ddas;

use App\Models\Ddas\Dealer;
use App\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessedDealerNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public function __construct(protected Dealer $dealer) {}


    public static function getName(): string {
        return __("Dealer's Den Application Processed");
    }

    protected function getVia(): array {
        return ['mail', 'telegram', 'database'];
    }

    protected function getSubject(): ?string {
        return __("Dealer's Den Application") . " - " .$this->dealer->name . ": " . $this->dealer->approval->name();
    }

    protected function getLines(): array {
        return [
            __('The status of your application for ":dealer" has been changed: :approval', ['dealer' => $this->dealer->name, 'approval' => $this->dealer->approval->name()]),
            "",
            $this->dealer->info_localized,
            "",
            $this->dealer->additional_info,
        ];
    }

    protected function getAction(): ?string {
        return __("View");
    }

    protected function getActionUrl(): string {
        return route("dealers.create");
    }

}
