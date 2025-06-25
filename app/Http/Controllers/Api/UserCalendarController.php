<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserCalendarSettings;
use App\Http\Controllers\Controller;
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
use Illuminate\Support\Facades\Gate;

class UserCalendarController extends Controller
{

    public function show(UserCalendar $calendar) {
        $export = new iCalExporter();

        Auth::loginUsingId($calendar->user_id);
        App::setLocale($calendar->user->preferredLocale());
        if(Gate::check("viewAny", TimetableEntry::class)) {
            if($calendar->settings->contains(UserCalendarSettings::SHOW_EVENTS->name) OR $calendar->settings->contains(UserCalendarSettings::SHOW_FAVORITES->name)) {
                $entries = TimetableEntry::public()->with(["sigEvent", "sigLocation", "favorites.reminders"])
                    ->where(function(Builder $query) use ($calendar) {
                        if(!$calendar->settings->contains(UserCalendarSettings::SHOW_EVENTS->name))
                            return $query->whereHas("favorites", function (Builder $query) use ($calendar) {
                                return $query->where("user_id", $calendar->user_id);
                            });
                        return $query;
                    })
                    ->get();

                foreach($entries AS $entry) {
                    $export->addTimetableEntry($entry, function(Event $event) use ($calendar, $entry) {
                        if($entry->favorites->where("user_id", auth()->user()->id)->count() == 0)
                            return $event;

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

            if($calendar->settings->contains(UserCalendarSettings::SHOW_TIMESLOTS->name)) {
                $calendar->user->load([
                    "sigTimeslots.reminders",
                    "sigTimeslots.timetableEntry",
                    "sigTimeslots.timetableEntry.sigLocation",
                    "sigTimeslots.timetableEntry.sigEvent",
                    "roles.shifts"
                ]);

                foreach($calendar->user->sigTimeslots AS $timeslot) {
                    $export->addTimeslot($timeslot);
                }
            }
        }

        if($calendar->settings->contains(UserCalendarSettings::SHOW_SHIFTS->name)) {
            foreach($calendar->user->userShifts()->with(["shift.type.userRole", "shift.sigLocation"])->get() AS $userShift) {
                $export->addShift($userShift->shift);
            }
            foreach($calendar->user->roles AS $role) {
                foreach($role->shifts->where("team") AS $shift) {
                    $export->addShift($shift);
                }
            }

        }

        return response($export->ical())
            ->header("Content-Type","text/calendar; charset=utf-8");
    }


}
