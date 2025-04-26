<?php

namespace App\Notifications\Messages;

use App\Notifications\Notification;
use App\Services\LanguageParser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class MessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected $subject, protected $body) {}

    // enforced channels
    protected function getVia(): array {
        return ['mail', 'telegram', 'database'];
    }

    protected function getSubject(): ?string {
        return LanguageParser::parse($this->subject);
    }

    protected function getLines(): array {
        return explode("\n", LanguageParser::parse($this->body));
    }

}
