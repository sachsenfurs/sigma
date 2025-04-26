<?php

namespace App\Notifications\Messages;

use App\Notifications\Notification;
use Illuminate\Bus\Queueable;

class MessageNotification extends Notification
{
    use Queueable;

    public function __construct(protected $subject, protected $body) {}

    // enforced channels
    protected function getVia(): array {
        return ['mail', 'telegram', 'database'];
    }

    protected function getSubject(): ?string {
        return __($this->subject);
    }

    protected function getLines(): array {
        return explode("\r\n", $this->body);
    }

}
