<?php

namespace App\Services\iCal;

use App\Models\Shift;
use App\Models\SigLocation;
use App\Models\SigTimeslot;
use App\Models\TimetableEntry;
use App\Settings\AppSettings;
use DateInterval;
use DateTimeZone as PhpDateTimeZone;
use Eluceo\iCal\Domain\Entity\Calendar;
use Eluceo\iCal\Domain\Entity\Event;
use Eluceo\iCal\Domain\Entity\TimeZone;
use Eluceo\iCal\Domain\Enum\EventStatus;
use Eluceo\iCal\Domain\ValueObject\Alarm;
use Eluceo\iCal\Domain\ValueObject\DateTime;
use Eluceo\iCal\Domain\ValueObject\Location;
use Eluceo\iCal\Domain\ValueObject\TimeSpan;
use Eluceo\iCal\Domain\ValueObject\Timestamp;
use Eluceo\iCal\Domain\ValueObject\UniqueIdentifier;
use Eluceo\iCal\Domain\ValueObject\Uri;
use Eluceo\iCal\Presentation\Component;
use Eluceo\iCal\Presentation\Component\Property;
use Eluceo\iCal\Presentation\Factory\CalendarFactory;
use Exception;
use Ramsey\Uuid\Uuid;

class iCalExporter
{
    private const NAMESPACE_UUID = "c004c41f-8740-4b8f-be42-cd92cd9e8b09"; // random but fixed UUID to generate deterministic UUIDs
    private Calendar $calendar;

    /**
     * @param Event[] $events
     * @throws Exception
     */
    public function __construct(array $events = []) {
        $phpDateTimeZone = new PhpDateTimeZone(config("app.timezone"));
        $timezone = TimeZone::createFromPhpDateTimeZone(
            $phpDateTimeZone,
            new \DateTimeImmutable('2020-01-01 00:00:00', $phpDateTimeZone),
            new \DateTimeImmutable('2021-01-01 00:00:00', $phpDateTimeZone)
        );
        $this->calendar = (new Calendar($events))
            ->addTimeZone($timezone)
            ->setProductIdentifier('-//sigma/ical//2.0/EN')
            ->setPublishedTTL(DateInterval::createFromDateString("1 hour"));
    }

    public function ical(): Component {
        $componentFactory = new CalendarFactory();
        return $componentFactory
            ->createCalendar($this->calendar)
            ->withProperty(new Property("REFRESH-INTERVAL;VALUE=DURATION", new Property\Value\TextValue("PT1H")))
            ->withProperty(new Property("X-WR-CALNAME", new Property\Value\TextValue(app(AppSettings::class)->event_name . " Events")));
    }


    private static function getBaseEvent(int $id, string $type): Event {
        return new Event(
            new UniqueIdentifier( // generate pseudo primary key
                Uuid::uuid5(
                    Uuid::fromString(self::NAMESPACE_UUID),
                    ($id . $type .  app(AppSettings::class)->event_start->day . config("app.key"))
                )
            )
        );
    }

    public static function getLocation(?SigLocation $location = null): ?Location {
        if(empty($location))
            return null;
        return new Location($location->name_localized, $location->description_localized);
    }

    /**
     * Loads relationships: sigEvent, sigLocation
     * @param TimetableEntry $entry
     * @param \Closure|null $modifyUsing
     */
    public function addTimetableEntry(TimetableEntry $entry, \Closure $modifyUsing = null): void {
        $event = self::getBaseEvent($entry->id, $entry::class)
            ->setSummary($entry->sigEvent->name_localized)
            ->setDescription($entry->sigEvent->description_localized)
            ->setLocation(self::getLocation($entry->sigLocation))
            ->setOccurrence(
                new TimeSpan(
                    new DateTime($entry->start, true),
                    new DateTime($entry->end, true)
                )
            )
            ->setStatus(EventStatus::CONFIRMED())
            ->setLastModified(new Timestamp($entry->updated_at))
            ->touch(new Timestamp($entry->created_at))
            ->setUrl(new Uri(route("timetable-entry.show", $entry)));

        if($modifyUsing)
            $event = $modifyUsing($event) ?? $event;

        $this->calendar->addEvent($event);
    }

    /**
     * Loads relationships: type, type.userRole, sigLocation
     * @param Shift $shift
     * @param \Closure|null $modifyUsing
     */
    public function addShift(Shift $shift, \Closure $modifyUsing = null): void {
        $event = self::getBaseEvent($shift->id, $shift::class)
            ->setSummary("❗ " . $shift->type->userRole->name_localized . "-" . __("Shift") . ": " . $shift->type->name)
            ->setLocation(self::getLocation($shift->sigLocation))
            ->setOccurrence(
                new TimeSpan(
                    new DateTime($shift->start, true),
                    new DateTime($shift->end, true)
                )
            )
            ->setDescription($shift->info ?? "")
            ->setStatus(EventStatus::CONFIRMED())
            ->setLastModified(new Timestamp($shift->updated_at))
            ->touch(new Timestamp($shift->created_at))
            ->addAlarm(
                new Alarm(
                    new Alarm\DisplayAction(
                        __(
                            'Your :department-shift ":type" starts in :min minutes!',
                            ['department' => $shift->type->userRole->name_localized, 'type' => $shift->type->name, 'min' => 15]
                        )
                    ),
                    new Alarm\RelativeTrigger(
                        DateInterval::createFromDateString("15 min ago")
                    )
                )
            );

        if($modifyUsing)
            $event = $modifyUsing($event) ?? $event;

        $this->calendar->addEvent($event);
    }

    /**
     * Loads relationships: timetableEntry.sigEvent, timetableEntry.sigLocation
     * @param SigTimeslot $slot
     * @param \Closure|null $modifyUsing
     * @return void
     */
    public function addTimeslot(SigTimeslot $slot, \Closure $modifyUsing = null): void {
        $event = self::getBaseEvent($slot->id, $slot::class)
            ->setSummary(__("Time Slot") . ": " . $slot->timetableEntry->sigEvent->name_localized)
            ->setLocation(self::getLocation($slot->timetableEntry->sigLocation))
            ->setOccurrence(
                new TimeSpan(
                    new DateTime($slot->slot_start, true),
                    new DateTime($slot->slot_end, true)
                )
            )
            ->setStatus(EventStatus::CONFIRMED())
            ->setLastModified(new Timestamp($slot->updated_at))
            ->touch(new Timestamp($slot->created_at));

        if($reminder = $slot->reminders->first()) {
             $event->addAlarm(
                 new Alarm(
                     new Alarm\DisplayAction(
                         __(
                             "Timeslot :time for :event starts in :min minutes!",
                             ['time' => $slot->slot_start->translatedFormat("H:i"), 'min' => $reminder->offset_minutes]
                         )
                     ),
                     new Alarm\RelativeTrigger(
                         DateInterval::createFromDateString("{$reminder->offset_minutes} min ago")
                     )
                 )
             );
        }
        if($modifyUsing)
            $event = $modifyUsing($event) ?? $event;

        $this->calendar->addEvent($event);
    }



}
