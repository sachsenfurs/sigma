<?php

namespace App\Notifications\Sig;

use App\Enums\Approval;
use App\Models\SigEvent;
use App\Notifications\Notification;
use App\Services\PageHookService;
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
            PageHookService::resolve("sigs.notification.application.processed") ?: "",
            $this->sigEvent->approval == Approval::APPROVED
                ? (PageHookService::resolve("sigs.notification.application.approved") ?: __("As soon as we have added your event and the preliminary schedule is ready, you will receive a separate notification. Please note that this may take a while!"))
                : "",
            $this->sigEvent->approval == Approval::REJECTED
                ? (PageHookService::resolve("sigs.notification.application.rejected") ?: __("Your event may have been classified as unsuitable or may not contain enough information. If you have any questions about this decision, please contact us!"))
                : "",
            __("The following description has been included for your event:"),
            $this->sigEvent->description_localized,
            __("Translation").":",
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
