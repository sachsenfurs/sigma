<?php

namespace App\Notifications\Forms;

use App\Enums\Approval;
use App\Models\SigFilledForm;
use App\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SigFilledFormProcessedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected SigFilledForm $filledForm) {}

    // enforced channels
    protected function getVia(): array {
        return ['telegram', 'mail', 'database'];
    }

    protected function getSubject(): ?string {
        return __(":form: :approval", [
            'form' => $this->filledForm->sigForm->name_localized,
            'approval' => $this->filledForm->approval->name(),
        ]);
    }

    protected function getLines(): array {
        return [
            __("Your submission for :form has been processed: :approval", [
                'form' => $this->filledForm->sigForm->name_localized,
                'approval' => $this->filledForm->approval->name()
            ]),
            ($this->filledForm->approval == Approval::REJECTED AND $this->filledForm->rejection_reason) ? __("Rejection reason").": " . $this->filledForm->rejection_reason : "",
        ];
    }

}
