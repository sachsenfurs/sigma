<?php

namespace App\Notifications\Sig;

use App\Models\SigReminder;
use App\Models\TimetableEntry;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class SigFavoriteReminder extends Notification
{
    use Queueable;

    protected TimetableEntry $timetableEntry;
    protected SigReminder $reminder;
    public static string $text = "*:event* starts in :minutes_before minutes!";
    public function __construct(TimetableEntry $entry, SigReminder $reminder) {
        $this->timetableEntry = $entry;
        $this->reminder = $reminder;
    }

    public function via(Model $notifiable): array {
        return NotificationService::channels($this, $notifiable);
    }

    public function toTelegram($notifiable) {
        return TelegramMessage::create()
            ->to($notifiable->telegram_user_id)
            ->line(
                __(self::$text, [
                    "event" => $this->timetableEntry->sigEvent->name_localized,
                    "minutes_before" => $this->reminder->minutes_before
                ])
            )
            ->line("")
            ->line(
                __("🕗 *Event Time:* :start - :end", [
                    'start' => $this->timetableEntry->start->format("H:i"),
                    'end' => $this->timetableEntry->end->format("H:i"),
                ])
            )
            ->line(
                __("📍 *Location:* :location", [
                    'location' => $this->timetableEntry->sigLocation->name_localized,
                ])
            )
            ->button(__("View Event") , route('timetable-entry.show', ['entry' => $this->timetableEntry->id]));
    }

    public function toMail(object $notifiable): MailMessage {
        return (new MailMessage)
            ->subject(
                '[INFO] ' . __(":event starting soon", [
                    'event' => $this->timetableEntry->sigEvent->name_localized
                ])
            )
            ->line(
                __(self::$text, [
                    "event" => $this->timetableEntry->sigEvent->name_localized,
                    "minutes_before" => $this->reminder->minutes_before
                ])
            )
            ->action(__('View Event'), route('timetable-entry.show', ['entry' => $this->timetableEntry->id]));
    }


    public function toArray($notifiable): array {
        return [
            'timetableEntryid' => $this->timetableEntry->id,
            'eventName' => $this->timetableEntry->sigEvent->name_localized,
            'minutes_before' => $this->reminder->minutes_before
        ];
    }
}
