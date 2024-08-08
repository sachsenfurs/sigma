<?php

namespace App\Notifications\SigFavorite;

use App;
use App\Models\SigReminder;
use App\Models\TimetableEntry;
use App\Models\UserNotificationChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class SigFavoriteReminder extends Notification
{
    use Queueable;

    protected $timetableEntry;
    protected $reminder;
    public static $text = "*:event* starts in :minutes_before minutes!";
    public function __construct(TimetableEntry $entry, SigReminder $reminder) {
        $this->timetableEntry = $entry;
        $this->reminder = $reminder;
    }


    public function via($notifiable) {
        return UserNotificationChannel::list('sig_favorite_reminder', $notifiable->id);
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


    public function toArray($notifiable) {
        return [
            'type' => 'sig_favorite_reminder',
            'timetableEntryid' => $this->timetableEntry->id,
            'eventName' => $this->timetableEntry->sigEvent->name_localized,
            'minutes_before' => $this->reminder->minutes_before
        ];
    }
}
