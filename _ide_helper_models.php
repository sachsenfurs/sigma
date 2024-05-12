<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models\Ddas{
/**
 * Model für die ArtshowArtist Tabelle damit diese sauber aufgebaut und mit anderen Tabelle verknüpft werden kann.
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $name
 * @property string|null $social Twitter, FA, Gallery, etc.
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ddas\ArtshowItem> $artshowItems
 * @property-read int|null $artshow_items_count
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\Ddas\ArtshowArtistFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowArtist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowArtist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowArtist query()
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowArtist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowArtist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowArtist whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowArtist whereSocial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowArtist whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowArtist whereUserId($value)
 */
	class ArtshowArtist extends \Eloquent {}
}

namespace App\Models\Ddas{
/**
 * Model für die ArtshowBid Tabelle damit diese sauber aufgebaut und mit anderen Tabelle verknüpft werden kann.
 *
 * @property int $id
 * @property int $artshow_item_id
 * @property string $value
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Ddas\ArtshowItem $artshowItem
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowBid newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowBid newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowBid query()
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowBid whereArtshowItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowBid whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowBid whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowBid whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowBid whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowBid whereValue($value)
 */
	class ArtshowBid extends \Eloquent {}
}

namespace App\Models\Ddas{
/**
 * App\Models\Ddas\ArtshowItem
 *
 * @property int $id
 * @property int $artshow_artist_id
 * @property string|null $name
 * @property string|null $description
 * @property string|null $description_en
 * @property string $starting_bid
 * @property string $charity_percentage
 * @property string|null $additional_info only visible for adminstration/auctioner
 * @property string|null $image
 * @property bool $auction
 * @property int $approved
 * @property bool $sold
 * @property bool $paid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Ddas\ArtshowArtist $artist
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ddas\ArtshowBid> $artshowBids
 * @property-read int|null $artshow_bids_count
 * @property-read \App\Models\Ddas\ArtshowPickup|null $artshowPickup
 * @property-read mixed $image_url
 * @method static \Database\Factories\Ddas\ArtshowItemFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowItem own()
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowItem whereAdditionalInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowItem whereApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowItem whereArtshowArtistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowItem whereAuction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowItem whereCharityPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowItem whereDescriptionEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowItem whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowItem whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowItem wherePaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowItem whereSold($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowItem whereStartingBid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowItem whereUpdatedAt($value)
 */
	class ArtshowItem extends \Eloquent {}
}

namespace App\Models\Ddas{
/**
 * App\Models\Ddas\ArtshowPickup
 *
 * @property int $id
 * @property int $artshow_item_id
 * @property int $user_id
 * @property string|null $info
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Ddas\ArtshowItem $artshowItem
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowPickup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowPickup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowPickup query()
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowPickup whereArtshowItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowPickup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowPickup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowPickup whereInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowPickup whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowPickup whereUserId($value)
 */
	class ArtshowPickup extends \Eloquent {}
}

namespace App\Models\Ddas{
/**
 * App\Models\Ddas\Dealer
 *
 * @property int $id
 * @property string $name
 * @property int|null $user_id
 * @property string|null $info
 * @property string|null $info_en
 * @property string|null $gallery_link
 * @property int $space
 * @property string|null $contact_way
 * @property string|null $contact
 * @property string|null $icon_file
 * @property int $approved
 * @property int|null $sig_location_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $info_localized
 * @property-read \App\Models\SigLocation|null $sigLocation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ddas\DealerTag> $tags
 * @property-read int|null $tags_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer approved()
 * @method static \Database\Factories\Ddas\DealerFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer whereApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer whereContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer whereContactWay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer whereGalleryLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer whereIconFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer whereInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer whereInfoEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer whereSigLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer whereSpace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer whereUserId($value)
 */
	class Dealer extends \Eloquent {}
}

namespace App\Models\Ddas{
/**
 * App\Models\Ddas\DealerTag
 *
 * @property int $id
 * @property string $name
 * @property string|null $name_en
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ddas\Dealer> $dealer
 * @property-read int|null $dealer_count
 * @property-read mixed $name_localized
 * @method static \Illuminate\Database\Eloquent\Builder|DealerTag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DealerTag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DealerTag query()
 * @method static \Illuminate\Database\Eloquent\Builder|DealerTag used()
 * @method static \Illuminate\Database\Eloquent\Builder|DealerTag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DealerTag whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DealerTag whereNameEn($value)
 */
	class DealerTag extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\LostFoundItem
 *
 * @property int $id
 * @property int $lassie_id
 * @property string|null $image_url
 * @property string|null $thumb_url
 * @property string $title
 * @property string|null $description
 * @property string|null $status L = lost, F = found
 * @property \Illuminate\Support\Carbon|null $lost_at
 * @property \Illuminate\Support\Carbon|null $found_at
 * @property \Illuminate\Support\Carbon|null $returned_at
 * @method static \Illuminate\Database\Eloquent\Builder|LostFoundItem found()
 * @method static \Illuminate\Database\Eloquent\Builder|LostFoundItem lost()
 * @method static \Illuminate\Database\Eloquent\Builder|LostFoundItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LostFoundItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LostFoundItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|LostFoundItem returned()
 * @method static \Illuminate\Database\Eloquent\Builder|LostFoundItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LostFoundItem whereFoundAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LostFoundItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LostFoundItem whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LostFoundItem whereLassieId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LostFoundItem whereLostAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LostFoundItem whereReturnedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LostFoundItem whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LostFoundItem whereThumbUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LostFoundItem whereTitle($value)
 */
	class LostFoundItem extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Permission
 *
 * @method static where(string $string, mixed $permissionName)
 * @method static create(array $array)
 * @property int $id
 * @property string $name
 * @property string $friendly_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserRole> $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereFriendlyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereUpdatedAt($value)
 */
	class Permission extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Post
 *
 * @property int $id
 * @property string $text_de
 * @property string $text_en
 * @property int|null $user_id
 * @property string $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PostChannel> $messages
 * @property-read int|null $messages_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post query()
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereTextDe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereTextEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUserId($value)
 */
	class Post extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PostChannel
 *
 * @property int $id
 * @property int $channel_identifier
 * @property string $language
 * @property string $implementation
 * @method static \Illuminate\Database\Eloquent\Builder|PostChannel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostChannel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostChannel query()
 * @method static \Illuminate\Database\Eloquent\Builder|PostChannel whereChannelIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostChannel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostChannel whereImplementation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostChannel whereLanguage($value)
 */
	class PostChannel extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PostChannelMessage
 *
 * @property-read \App\Models\Post|null $post
 * @property-read \App\Models\PostChannel|null $postChannel
 * @method static \Illuminate\Database\Eloquent\Builder|PostChannelMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostChannelMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostChannelMessage query()
 */
	class PostChannelMessage extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SigAttendee
 *
 * @property int $id
 * @property int $user_id
 * @property int $sig_timeslot_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\SigTimeslot $sigTimeslot
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|SigAttendee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigAttendee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigAttendee query()
 * @method static \Illuminate\Database\Eloquent\Builder|SigAttendee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigAttendee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigAttendee whereSigTimeslotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigAttendee whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigAttendee whereUserId($value)
 */
	class SigAttendee extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SigEvent
 *
 * @property int $id
 * @property string $name
 * @property string $name_en
 * @property int|null $sig_host_id
 * @property array $languages two letter language code as JSON array
 * @property string|null $description
 * @property string|null $description_en
 * @property int $duration
 * @property int $approved
 * @property string|null $additional_info
 * @property mixed|null $requirements
 * @property int $reg_possible
 * @property int $max_regs_per_day
 * @property int $max_group_attendees_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SigForms> $forms
 * @property-read int|null $forms_count
 * @property-read mixed $description_localized
 * @property-read mixed $name_localized
 * @property-read mixed $timetable_count
 * @property-read \App\Models\SigHost|null $sigHost
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SigTag> $sigTags
 * @property-read int|null $sig_tags_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TimetableEntry> $timetableEntries
 * @property-read int|null $timetable_entries_count
 * @method static \Database\Factories\SigEventFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent public()
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent query()
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent whereAdditionalInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent whereApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent whereDescriptionEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent whereLanguages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent whereMaxGroupAttendeesCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent whereMaxRegsPerDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent whereRegPossible($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent whereRequirements($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent whereSigHostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent whereUpdatedAt($value)
 */
	class SigEvent extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SigFavorite
 *
 * @property int $id
 * @property int $user_id
 * @property int $timetable_entry_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\TimetableEntry $timetableEntry
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|SigFavorite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigFavorite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigFavorite query()
 * @method static \Illuminate\Database\Eloquent\Builder|SigFavorite upcoming()
 * @method static \Illuminate\Database\Eloquent\Builder|SigFavorite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigFavorite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigFavorite whereTimetableEntryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigFavorite whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigFavorite whereUserId($value)
 */
	class SigFavorite extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SigFilledForms
 *
 * @property int $id
 * @property int $sig_forms_id
 * @property int $user_id
 * @property array|null $form_data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\SigForms $sigForms
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|SigFilledForms newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigFilledForms newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigFilledForms query()
 * @method static \Illuminate\Database\Eloquent\Builder|SigFilledForms whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigFilledForms whereFormData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigFilledForms whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigFilledForms whereSigFormsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigFilledForms whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigFilledForms whereUserId($value)
 */
	class SigFilledForms extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SigForms
 *
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property string $name_en
 * @property int|null $sig_event_id
 * @property array|null $form_definition
 * @property int $form_closed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $name_localized
 * @property-read \App\Models\SigEvent|null $sigEvent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SigFilledForms> $sigFilledForms
 * @property-read int|null $sig_filled_forms_count
 * @method static \Illuminate\Database\Eloquent\Builder|SigForms newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigForms newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigForms query()
 * @method static \Illuminate\Database\Eloquent\Builder|SigForms whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigForms whereFormClosed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigForms whereFormDefinition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigForms whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigForms whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigForms whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigForms whereSigEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigForms whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigForms whereUpdatedAt($value)
 */
	class SigForms extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SigHost
 *
 * @property int $id
 * @property string $name
 * @property int|null $reg_id
 * @property string|null $description
 * @property string|null $description_en
 * @property bool $hide
 * @property-read mixed $avatar
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SigEvent> $sigEvents
 * @property-read int|null $sig_events_count
 * @property-read mixed $slug
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TimetableEntry> $timetableEntries
 * @property-read int|null $timetable_entries_count
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\SigHostFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|SigHost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigHost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigHost public()
 * @method static \Illuminate\Database\Eloquent\Builder|SigHost query()
 * @method static \Illuminate\Database\Eloquent\Builder|SigHost whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigHost whereDescriptionEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigHost whereHide($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigHost whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigHost whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigHost whereRegId($value)
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
 * @property string|null $description_en
 * @property array|null $render_ids layer id for displaying as interactive SVG or whatever
 * @property string|null $floor
 * @property string|null $room
 * @property bool $infodisplay Is there a digital display in front of the door? (Signage)
 * @property string|null $roomsize
 * @property string|null $seats
 * @property bool $show_default Show in calendar view (resource view) by default?
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ddas\Dealer> $dealers
 * @property-read int|null $dealers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SigEvent> $sigEvents
 * @property-read int|null $sig_events_count
 * @property-read mixed $slug
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TimetableEntry> $timetableEntries
 * @property-read int|null $timetable_entries_count
 * @method static \Database\Factories\SigLocationFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocation query()
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocation whereDescriptionEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocation whereFloor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocation whereInfodisplay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocation whereRenderIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocation whereRoom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocation whereRoomsize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocation whereSeats($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocation whereShowDefault($value)
 */
	class SigLocation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SigReminder
 *
 * @property int $id
 * @property int $user_id
 * @property int $timetable_entry_id
 * @property int $send_at
 * @property int|null $executed_at
 * @property string|null $result
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\TimetableEntry $timetableEntry
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|SigReminder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigReminder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigReminder query()
 * @method static \Illuminate\Database\Eloquent\Builder|SigReminder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigReminder whereExecutedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigReminder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigReminder whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigReminder whereSendAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigReminder whereTimetableEntryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigReminder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigReminder whereUserId($value)
 */
	class SigReminder extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SigTag
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $description_en
 * @property string|null $icon
 * @property-read string $description_localized
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SigEvent> $sigEvent
 * @property-read int|null $sig_event_count
 * @method static \Illuminate\Database\Eloquent\Builder|SigTag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigTag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigTag query()
 * @method static \Illuminate\Database\Eloquent\Builder|SigTag whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigTag whereDescriptionEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigTag whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigTag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigTag whereName($value)
 */
	class SigTag extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SigTimeslot
 *
 * @property int $id
 * @property int $timetable_entry_id
 * @property int $max_users
 * @property string $slot_start
 * @property string $slot_end
 * @property \Illuminate\Support\Carbon|null $reg_start
 * @property \Illuminate\Support\Carbon|null $reg_end
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $notes
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SigAttendee> $sigAttendees
 * @property-read int|null $sig_attendees_count
 * @property-read \App\Models\TimetableEntry $timetableEntry
 * @method static \Illuminate\Database\Eloquent\Builder|SigTimeslot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigTimeslot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigTimeslot query()
 * @method static \Illuminate\Database\Eloquent\Builder|SigTimeslot whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigTimeslot whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigTimeslot whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigTimeslot whereMaxUsers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigTimeslot whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigTimeslot whereRegEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigTimeslot whereRegStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigTimeslot whereSlotEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigTimeslot whereSlotStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigTimeslot whereTimetableEntryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigTimeslot whereUpdatedAt($value)
 */
	class SigTimeslot extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SigTimeslotReminder
 *
 * @property int $id
 * @property int $user_id
 * @property int $timeslot_id
 * @property int $minutes_before
 * @property int $send_at
 * @property int|null $executed_at
 * @property string|null $result
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\SigTimeslot $timeslot
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|SigTimeslotReminder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigTimeslotReminder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigTimeslotReminder query()
 * @method static \Illuminate\Database\Eloquent\Builder|SigTimeslotReminder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigTimeslotReminder whereExecutedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigTimeslotReminder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigTimeslotReminder whereMinutesBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigTimeslotReminder whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigTimeslotReminder whereSendAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigTimeslotReminder whereTimeslotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigTimeslotReminder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigTimeslotReminder whereUserId($value)
 */
	class SigTimeslotReminder extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TimetableEntry
 *
 * @property int $id
 * @property int $sig_event_id
 * @property int $sig_location_id
 * @property \Illuminate\Support\Carbon $start
 * @property \Illuminate\Support\Carbon $end
 * @property bool $cancelled
 * @property int|null $replaced_by_id
 * @property bool $hide
 * @property int $new
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $duration
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SigFavorite> $favorites
 * @property-read int|null $favorites_count
 * @property-read mixed $formatted_length
 * @property-read bool $has_location_changed
 * @property-read bool $has_time_changed
 * @property-read mixed $is_favorite
 * @property-read TimetableEntry|null $parentEntry
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SigReminder> $reminders
 * @property-read int|null $reminders_count
 * @property-read TimetableEntry|null $replacedBy
 * @property-read \App\Models\SigEvent $sigEvent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SigEvent> $sigEvents
 * @property-read int|null $sig_events_count
 * @property-read \App\Models\SigLocation $sigLocation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SigTimeslot> $sigTimeslots
 * @property-read int|null $sig_timeslots_count
 * @method static \Database\Factories\TimetableEntryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|TimetableEntry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TimetableEntry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TimetableEntry noAnnouncements()
 * @method static \Illuminate\Database\Eloquent\Builder|TimetableEntry public()
 * @method static \Illuminate\Database\Eloquent\Builder|TimetableEntry query()
 * @method static \Illuminate\Database\Eloquent\Builder|TimetableEntry whereCancelled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimetableEntry whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimetableEntry whereEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimetableEntry whereHide($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimetableEntry whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimetableEntry whereNew($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimetableEntry whereReplacedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimetableEntry whereSigEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimetableEntry whereSigLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimetableEntry whereStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimetableEntry whereUpdatedAt($value)
 */
	class TimetableEntry extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property int|null $reg_id
 * @property string|null $language
 * @property int|null $telegram_id
 * @property array $groups
 * @property string|null $avatar
 * @property string|null $avatar_thumb
 * @property string|null $telegram_user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ddas\ArtshowArtist> $artists
 * @property-read int|null $artists_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ddas\ArtshowBid> $artshowBids
 * @property-read int|null $artshow_bids_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SigAttendee> $attendeeEvents
 * @property-read int|null $attendee_events_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ddas\Dealer> $dealers
 * @property-read int|null $dealers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SigFavorite> $favorites
 * @property-read int|null $favorites_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Post> $posts
 * @property-read int|null $posts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SigReminder> $reminders
 * @property-read int|null $reminders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserRole> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SigHost> $sigHosts
 * @property-read int|null $sig_hosts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SigTimeslot> $sigTimeslots
 * @property-read int|null $sig_timeslots_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SigTimeslotReminder> $timeslotReminders
 * @property-read int|null $timeslot_reminders_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatarThumb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGroups($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRegId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTelegramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTelegramUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent implements \Filament\Models\Contracts\FilamentUser, \Filament\Models\Contracts\HasAvatar {}
}

namespace App\Models{
/**
 * App\Models\UserRole
 *
 * @property int $id
 * @property string $title
 * @property string $fore_color
 * @property string $border_color
 * @property string $background_color
 * @property string|null $registration_system_key
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereBackgroundColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereBorderColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereForeColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereRegistrationSystemKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereUpdatedAt($value)
 */
	class UserRole extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UserUserRole
 *
 * @property int $id
 * @property int $user_id
 * @property int $user_role_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\UserRole|null $role
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserUserRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserUserRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserUserRole query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserUserRole whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserUserRole whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserUserRole whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserUserRole whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserUserRole whereUserRoleId($value)
 */
	class UserUserRole extends \Eloquent {}
}

