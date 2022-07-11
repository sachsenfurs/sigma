<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\SigEvent
 *
 * @property int $id
 * @property string $name
 * @property int|null $sig_host_id
 * @property string $default_language defines the language for name & description, Other languages will be translated using sig_translations
 * @property array $languages two letter language code as JSON array
 * @property string $description
 * @property int $sig_location_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $time_table_count
 * @property-read \App\Models\SigHost|null $sigHost
 * @property-read \App\Models\SigLocation $sigLocation
 * @property-read \App\Models\SigTranslation|null $sigTranslation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TimeTableEntry[] $timeTableEntries
 * @property-read int|null $time_table_entries_count
 * @method static \Database\Factories\SigEventFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent query()
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent whereDefaultLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent whereLanguages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent whereSigHostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent whereSigLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent whereUpdatedAt($value)
 */
	class SigEvent extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SigHost
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SigEvent[] $sigEvents
 * @property-read int|null $sig_events_count
 * @method static \Database\Factories\SigHostFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|SigHost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigHost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigHost query()
 * @method static \Illuminate\Database\Eloquent\Builder|SigHost whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigHost whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigHost whereName($value)
 */
	class SigHost extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SigLocation
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property array|null $render_ids layer id for displaying as interactive SVG or whatever
 * @property string|null $floor
 * @property string|null $room
 * @property string|null $roomsize
 * @property string|null $seats
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SigEvent[] $sigEvents
 * @property-read int|null $sig_events_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SigLocationTranslation[] $translation
 * @property-read int|null $translation_count
 * @method static \Database\Factories\SigLocationFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocation query()
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocation whereFloor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocation whereRenderIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocation whereRoom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocation whereRoomsize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocation whereSeats($value)
 */
	class SigLocation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SigLocationTranslation
 *
 * @property int $sig_location_id
 * @property string $language Language for this particular translation entry
 * @property string $name
 * @property string $description
 * @property-read \App\Models\SigLocation $sigLocation
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocationTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocationTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocationTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocationTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocationTranslation whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocationTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocationTranslation whereSigLocationId($value)
 */
	class SigLocationTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SigTag
 *
 * @property int $id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|SigTag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigTag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigTag query()
 * @method static \Illuminate\Database\Eloquent\Builder|SigTag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigTag whereName($value)
 */
	class SigTag extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SigTranslation
 *
 * @property int $sig_event_id
 * @property string $language Language for this particular translation entry
 * @property string $name
 * @property string $description
 * @method static \Database\Factories\SigTranslationFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|SigTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|SigTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigTranslation whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigTranslation whereSigEventId($value)
 */
	class SigTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TimeTableEntry
 *
 * @property int $id
 * @property int $sig_event_id
 * @property int|null $sig_location_id
 * @property string $start
 * @property string $end
 * @property int $cancelled
 * @property int|null $replaced_by_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read TimeTableEntry|null $parentEntry
 * @property-read TimeTableEntry|null $replacedBy
 * @property-read \App\Models\SigEvent $sigEvent
 * @method static \Database\Factories\TimeTableEntryFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeTableEntry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TimeTableEntry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TimeTableEntry query()
 * @method static \Illuminate\Database\Eloquent\Builder|TimeTableEntry whereCancelled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeTableEntry whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeTableEntry whereEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeTableEntry whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeTableEntry whereReplacedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeTableEntry whereSigEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeTableEntry whereSigLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeTableEntry whereStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeTableEntry whereUpdatedAt($value)
 */
	class TimeTableEntry extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property int $is_admin
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

