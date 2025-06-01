<?php

namespace App\Http\Controllers;

use App\Models\TimetableEntry;
use App\Models\UserCalendar;
use App\Services\iCal\iCalExporter;
use DateInterval;
use Eluceo\iCal\Domain\Entity\Event;
use Eluceo\iCal\Domain\ValueObject\Alarm;
use Eluceo\iCal\Domain\ValueObject\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class UserCalendarController extends Controller
{

    public function show(UserCalendar $calendar) {
        $export = new iCalExporter();

        Auth::loginUsingId($calendar->user_id);
        App::setLocale($calendar->user->preferredLocale());

        if(data_get($calendar->settings, "show_events") OR data_get($calendar->settings, "show_favorites")) {
            $entries = TimetableEntry::public()->with(["sigEvent", "sigLocation", "favorites"])
                ->where(function(Builder $query) use ($calendar) {
                    if(!data_get($calendar->settings, "show_events"))
                        return $query->whereHas("favorites", function (Builder $query) use ($calendar) {
                            return $query->where("user_id", $calendar->user_id);
                        });
                    return $query;
                })
                ->get();

            foreach($entries AS $entry) {
                $export->addTimetableEntry($entry, function(Event $event) use ($calendar, $entry) {
                    $event->setSummary("❤ " . $event->getSummary())
                          ->addCategory(new Category(__("Favorite")));

                    if($reminder = $entry->favorites->where("user_id", $calendar->user_id)->first()?->reminders?->first() AND $favorite = $reminder->remindable) {
                        return $event
                            ->addAlarm(
                                new Alarm(
                                    new Alarm\DisplayAction(
                                        __(
                                            "Your favorite event **:sig** starts in :min minutes!",
                                            ['sig' => $favorite->timetableEntry->sigEvent->name_localized, 'min' => $reminder->offset_minutes]
                                        )
                                    ),
                                    new Alarm\RelativeTrigger(
                                        DateInterval::createFromDateString("{$reminder->offset_minutes} min ago")
                                    )
                                )
                            )
                        ;
                    }
                    return $event;
                });
            }
        }

        if(data_get($calendar->settings, "show_timeslots")) {
            $calendar->user->load([
                "sigTimeslots.reminders",
                "sigTimeslots.timetableEntry",
                "sigTimeslots.timetableEntry.sigLocation",
                "sigTimeslots.timetableEntry.sigEvent"
            ]);

            foreach($calendar->user->sigTimeslots AS $timeslot) {
                $export->addTimeslot($timeslot);
            }
        }

        if(data_get($calendar->settings, "show_shifts")) {
            foreach($calendar->user->userShifts()->with(["shift.type.userRole", "shift.sigLocation"])->get() AS $userShift) {
                $export->addShift($userShift->shift);
            }
        }

        return response($export->ical())->header("Content-Type","text/calendar; charset=utf-8");
    }

}
