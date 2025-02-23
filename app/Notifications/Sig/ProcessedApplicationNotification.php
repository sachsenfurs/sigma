<?php

namespace App\Notifications\Sig;

use App\Models\SigEvent;
use App\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessedApplicationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public static bool $userSetting = false;
    public function __construct(protected SigEvent $sigEvent) {}


    public static function getName(): string {
        return __("SIG Application Processed");
    }

    protected function getVia(): array {
        return ['mail', 'telegram', 'database'];
    }

    protected function getSubject(): ?string {
        return __(":sig: :approval", ['sig' => $this->sigEvent->name_localized, 'approval' => $this->sigEvent->approval->name()]);
    }

    protected function getLines(): array {
        return [
            __("The status of your submitted SIG :sig has been changed: :approval", ['sig' => $this->sigEvent->name_localized, 'approval' => $this->sigEvent->approval->name()]),
            "",
            $this->sigEvent->description_localized,
            "",
            $this->sigEvent->description_localized_other,
        ];
    }

    protected function getAction(): ?string {
        return __("Review");
    }

    protected function getActionUrl(): string {
        return route("sigs.index");
    }

}
