<?php

namespace App\Notifications\Sig;

use App\Filament\Resources\SigEventResource;
use App\Models\SigEvent;
use App\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewSigApplicationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public static bool $userSetting = false;
    public function __construct(protected SigEvent $sigEvent) {}


    public static function getName(): string {
        return __("New SIG Application");
    }

    protected function getVia(): array {
        return ['mail', 'telegram'];
    }

    protected function getSubject(): ?string {
        return __("New SIG Application: :sig", ['sig' => $this->sigEvent->name]);
    }

    protected function getLines(): array {
        return [
            __(":name has registered the SIG :sig:", ['name' => $this->sigEvent->primary_host->name, 'sig' => $this->sigEvent->name]),
            $this->sigEvent->description,
        ];
    }

    protected function getAction(): ?string {
        return __("Review");
    }

    protected function getActionUrl(): string {
        return SigEventResource::getUrl('edit', ['record' => $this->sigEvent]);
    }
}
