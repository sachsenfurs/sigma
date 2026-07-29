<?php

namespace App\Services;

use App\Enums\Approval;
use App\Models\SigEvent;
use App\Models\SigHost;
use App\Models\SigLocation;
use App\Models\SigTag;
use App\Models\TimetableEntry;
use App\Settings\AppSettings;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Kidran\EventScheduleSchema\Data\EventSchedule;
use Kidran\EventScheduleSchema\Facades\EventScheduleSchema as Schedule;

class BarqScheduleBuilder
{
    private const VENUE_ID = 'hotel';
    private const SESSION_TYPE_ID = 'sig';

    public function build(): EventSchedule {
        $entries = $this->entries();
        $updatedAt = $this->updatedAt($entries);

        $event = Schedule::event(
            id: $this->eventId(),
            displayName: $this->localized(app(AppSettings::class)->event_name),
            startTime: app(AppSettings::class)->event_start,
            endTime: app(AppSettings::class)->event_end,
            timezone: config('app.timezone', 'Europe/Berlin'),
        )
            ->imageThumbnailUrl($this->absoluteUrl(app(AppSettings::class)->logoUrl()));

        $schedule = Schedule::schedule(
            event: $event,
            updatedAt: $updatedAt,
        )->source($this->withoutEmpty([
            'name' => 'SIGMA',
            'vendorId' => config('app.url'),
            'appVersion' => app()->version(),
            'lastModifiedAt' => $updatedAt->toAtomString(),
        ]));

        $schedule->addVenue(
            Schedule::venue(self::VENUE_ID, $this->localized(app(AppSettings::class)->event_name))
        );

        $schedule->addSessionType(
            Schedule::sessionType(self::SESSION_TYPE_ID, $this->localized('SIG'))
                ->description($this->localized('Programmplaneintrag', 'Schedule entry'))
        );

        $this->usedLocations($entries)
            ->each(fn(SigLocation $location) => $schedule->addRoom($this->room($location)));

        $this->usedHosts($entries)
            ->each(fn(SigHost $host) => $schedule->addHost($this->host($host)));

        $this->usedTags($entries)
            ->each(fn(SigTag $tag) => $schedule->addLabel($this->label($tag)));

        $entries
            ->groupBy('sig_event_id')
            ->each(fn(Collection $eventEntries) => $schedule->addSession($this->session($eventEntries)));

        return $schedule;
    }

    private function entries(): EloquentCollection {
        return TimetableEntry::query()
            ->noAnnouncements()
            ->public()
            ->whereHas('sigEvent', fn($query) => $query->where('approval', Approval::APPROVED->value))
            ->with([
                'sigLocation',
                'sigEvent.sigHosts',
                'sigEvent.sigTags',
            ])
            ->orderBy('start')
            ->get();
    }

    private function room(SigLocation $location): \Kidran\EventScheduleSchema\Data\Room {
        $room = Schedule::room(
            id: $this->locationId($location),
            displayName: $this->localized($location->name, $location->name_en),
            venueId: self::VENUE_ID,
        )
            ->description($this->localized($location->description, $location->description_en));

        if(is_numeric($location->seats)) {
            $room->capacity((int) $location->seats);
        }

        return $room;
    }

    private function host(SigHost $host): \Kidran\EventScheduleSchema\Data\Host {
        return Schedule::host($this->hostId($host), $host->name)
            ->imageThumbnailUrl($this->absoluteUrl($host->avatar_thumb))
            ->imageBannerUrl($this->absoluteUrl($host->avatar));
    }

    private function label(SigTag $tag): \Kidran\EventScheduleSchema\Data\Label {
        return Schedule::label(
            id: $this->tagId($tag),
            displayName: $this->localized($tag->description, $tag->description_en),
        )
            ->description($this->localized($tag->description, $tag->description_en));
    }

    private function session(Collection $entries): \Kidran\EventScheduleSchema\Data\Session {
        /** @var TimetableEntry $firstEntry */
        $firstEntry = $entries->first();
        $sigEvent = $firstEntry->sigEvent;

        $session = Schedule::session(
            id: $this->sessionId($sigEvent),
            displayName: $this->localized($sigEvent->name, $sigEvent->name_en),
        )
            ->description($this->localized($sigEvent->description, $sigEvent->description_en))
            ->type(self::SESSION_TYPE_ID)
            ->labels($sigEvent->sigTags->map(fn(SigTag $tag) => $this->tagId($tag))->values()->all())
            ->ticketed($this->attributeBool($sigEvent, 'ticketed'))
            ->minAge($this->attributeInt($sigEvent, 'minAge') ?? $this->attributeInt($sigEvent, 'min_age'))
            ->addExternalLink(Schedule::externalLink($this->localized('Im Programmplan anzeigen', 'View in schedule'), route('timetable-entry.show', $firstEntry)));

        $entries
            ->sortBy('start')
            ->each(fn(TimetableEntry $entry) => $session->addTimeSlot($this->timeSlot($entry)));

        return $session;
    }

    private function timeSlot(TimetableEntry $entry): \Kidran\EventScheduleSchema\Data\TimeSlot {
        return Schedule::timeSlot($entry->start, $entry->end)
            ->id($this->timeSlotId($entry))
            ->rooms([$this->locationId($entry->sigLocation)])
            ->hosts($entry->sigEvent->sigHosts
                ->filter(fn(SigHost $host) => ! $host->hide)
                ->map(fn(SigHost $host) => $this->hostId($host))
                ->values()
                ->all());
    }

    private function usedLocations(EloquentCollection $entries): Collection {
        return $entries
            ->pluck('sigLocation')
            ->filter()
            ->unique('id')
            ->sortBy('name')
            ->values();
    }

    private function usedHosts(EloquentCollection $entries): Collection {
        return $entries
            ->flatMap(fn(TimetableEntry $entry) => $entry->sigEvent->sigHosts)
            ->filter(fn(SigHost $host) => ! $host->hide)
            ->unique('id')
            ->sortBy('name')
            ->values();
    }

    private function usedTags(EloquentCollection $entries): Collection {
        return $entries
            ->flatMap(fn(TimetableEntry $entry) => $entry->sigEvent->sigTags)
            ->unique('id')
            ->sortBy('name')
            ->values();
    }

    private function updatedAt(EloquentCollection $entries): CarbonInterface {
        return collect([
            app(AppSettings::class)->event_start,
            app(AppSettings::class)->event_end,
            ...$entries->pluck('updated_at'),
            ...$entries->pluck('sigEvent.updated_at'),
        ])
            ->filter()
            ->map(fn($date) => $date instanceof CarbonInterface ? $date : Carbon::parse($date))
            ->sortByDesc(fn(CarbonInterface $date) => $date->getTimestamp())
            ->first() ?? now();
    }

    private function localized(?string $de, ?string $en = null): array {
        $de = filled($de) ? $de : $en;
        $en = filled($en) ? $en : $de;

        return array_filter([
            'de-DE' => $de,
            'en-US' => $en,
        ], fn(?string $value): bool => filled($value));
    }

    private function withoutEmpty(array $values): array {
        return array_filter($values, fn($value): bool => filled($value) || is_bool($value) || is_int($value));
    }

    private function absoluteUrl(?string $url): ?string {
        if(! filled($url)) {
            return null;
        }

        return Str::startsWith($url, ['http://', 'https://']) ? $url : url($url);
    }

    private function attributeBool(SigEvent $event, string $key): ?bool {
        if(! array_key_exists($key, $event->attributes ?? [])) {
            return null;
        }

        return filter_var($event->attributes[$key], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }

    private function attributeInt(SigEvent $event, string $key): ?int {
        if(! array_key_exists($key, $event->attributes ?? []) || ! is_numeric($event->attributes[$key])) {
            return null;
        }

        return (int) $event->attributes[$key];
    }

    private function eventId(): string {
        return Str::slug(app(AppSettings::class)->event_name) ?: 'sig-event';
    }

    private function locationId(SigLocation $location): string {
        return 'sig-location-'.$location->id;
    }

    private function hostId(SigHost $host): string {
        return 'sig-host-'.$host->id;
    }

    private function tagId(SigTag $tag): string {
        return 'sig-tag-'.$tag->id;
    }

    private function sessionId(SigEvent $event): string {
        return 'sig-event-'.$event->id;
    }

    private function timeSlotId(TimetableEntry $entry): string {
        return 'timetable-entry-'.$entry->id;
    }
}
