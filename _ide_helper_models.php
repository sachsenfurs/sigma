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
 * 
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
 * 
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
 * 
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
 * @property int $locked
 * @property \App\Enums\Approval $approval 0 => Pending, 1 => Approved, 2 => Rejected
 * @property bool $sold
 * @property bool $paid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $approved
 * @property-read \App\Models\Ddas\ArtshowArtist $artist
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ddas\ArtshowBid> $artshowBids
 * @property-read int|null $artshow_bids_count
 * @property-read \App\Models\Ddas\ArtshowPickup|null $artshowPickup
 * @property-read mixed $image_url
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowItem approved()
 * @method static \Database\Factories\Ddas\ArtshowItemFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowItem own()
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowItem whereAdditionalInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowItem whereApproval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowItem whereArtshowArtistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowItem whereAuction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowItem whereCharityPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowItem whereDescriptionEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowItem whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArtshowItem whereLocked($value)
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
 * 
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
 * 
 *
 * @property int $id
 * @property string $name
 * @property int|null $user_id
 * @property string|null $info
 * @property string|null $info_en
 * @property string|null $gallery_link
 * @property string|null $additional_info
 * @property string|null $icon_file
 * @property \App\Enums\Approval $approval 0 => Pending, 1 => Approved, 2 => Rejected
 * @property int|null $sig_location_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $gallery_link_name
 * @property-read mixed $icon_file_url
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
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer whereAdditionalInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer whereApproval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer whereGalleryLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer whereIconFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer whereInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer whereInfoEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer whereSigLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer whereUserId($value)
 */
	class Dealer extends \Eloquent {}
}

namespace App\Models\Ddas{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $name_en
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ddas\Dealer> $dealers
 * @property-read int|null $dealers_count
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
 * 
 *
 * @property int $id
 * @property int $sig_event_id
 * @property int $user_role_id
 * @property string|null $additional_info
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\SigEvent $sigEvent
 * @property-read \App\Models\UserRole $userRole
 * @method static \Illuminate\Database\Eloquent\Builder|DepartmentInfo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DepartmentInfo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DepartmentInfo query()
 * @method static \Illuminate\Database\Eloquent\Builder|DepartmentInfo whereAdditionalInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DepartmentInfo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DepartmentInfo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DepartmentInfo whereSigEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DepartmentInfo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DepartmentInfo whereUserRoleId($value)
 */
	class DepartmentInfo extends \Eloquent {}
}

namespace App\Models\Info{
/**
 * 
 *
 * @property int $id
 * @property string $description
 * @property string|null $description_en
 * @property string $link
 * @property string|null $link_en
 * @property string|null $link_name
 * @property string|null $link_name_en
 * @property string|null $icon
 * @property string|null $image
 * @property string|null $image_en
 * @property array $show_on
 * @property int $order
 * @property-read mixed $description_localized
 * @property-read mixed $image_url
 * @property-read mixed $image_url_en
 * @property-read mixed $link_localized
 * @property-read mixed $link_name_localized
 * @method static \Illuminate\Database\Eloquent\Builder|Social footerIcon()
 * @method static \Illuminate\Database\Eloquent\Builder|Social footerText()
 * @method static \Illuminate\Database\Eloquent\Builder|Social newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Social newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Social query()
 * @method static \Illuminate\Database\Eloquent\Builder|Social signage()
 * @method static \Illuminate\Database\Eloquent\Builder|Social whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Social whereDescriptionEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Social whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Social whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Social whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Social whereImageEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Social whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Social whereLinkEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Social whereLinkName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Social whereLinkNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Social whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Social whereShowOn($value)
 */
	class Social extends \Eloquent {}
}

namespace App\Models{
/**
 * 
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

namespace App\Models\Post{
/**
 * 
 *
 * @property int $id
 * @property string|null $text
 * @property string|null $text_en
 * @property int|null $user_id
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Post\PostChannel> $channels
 * @property-read int|null $channels_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Post\PostChannelMessage> $messages
 * @property-read int|null $messages_count
 * @property-read mixed $text_localized
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Post query()
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereTextEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Post withoutTrashed()
 */
	class Post extends \Eloquent {}
}

namespace App\Models\Post{
/**
 * 
 *
 * @property int $id
 * @property int $channel_identifier
 * @property int|null $test_channel_identifier
 * @property string|null $name
 * @property string $language
 * @property string $implementation
 * @property string|null $info
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Post\Post> $posts
 * @property-read int|null $posts_count
 * @method static \Illuminate\Database\Eloquent\Builder|PostChannel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostChannel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostChannel query()
 * @method static \Illuminate\Database\Eloquent\Builder|PostChannel whereChannelIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostChannel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostChannel whereImplementation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostChannel whereInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostChannel whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostChannel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostChannel whereTestChannelIdentifier($value)
 */
	class PostChannel extends \Eloquent {}
}

namespace App\Models\Post{
/**
 * 
 *
 * @property int $id
 * @property int $post_id
 * @property int $post_channel_id
 * @property int $message_id
 * @property-read \App\Models\Post\Post $post
 * @property-read \App\Models\Post\PostChannel $postChannel
 * @method static \Illuminate\Database\Eloquent\Builder|PostChannelMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostChannelMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostChannelMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder|PostChannelMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostChannelMessage whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostChannelMessage wherePostChannelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostChannelMessage wherePostId($value)
 */
	class PostChannelMessage extends \Eloquent {}
}

namespace App\Models{
/**
 * 
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
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $name_en
 * @property int|null $sig_host_id
 * @property array $languages two letter language code as JSON array
 * @property string|null $description
 * @property string|null $description_en
 * @property int $text_confirmed
 * @property int $no_text
 * @property int $duration
 * @property \App\Enums\Approval $approval 0 => Pending, 1 => Approved, 2 => Rejected
 * @property string|null $additional_info
 * @property string|null $requirements
 * @property int $reg_possible
 * @property int $max_regs_per_day
 * @property int $max_group_attendees_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $approved
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DepartmentInfo> $departmentInfos
 * @property-read int|null $department_infos_count
 * @property-read mixed $description_localized
 * @property-read mixed $description_localized_other
 * @property-read mixed $duration_hours
 * @property-read mixed $favorite_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SigForm> $forms
 * @property-read int|null $forms_count
 * @property-read mixed $name_localized
 * @property-read mixed $name_localized_other
 * @property-read mixed $primary_host
 * @property-read mixed $public_hosts
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SigHost> $sigHosts
 * @property-read int|null $sig_hosts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SigTag> $sigTags
 * @property-read int|null $sig_tags_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SigTimeslot> $sigTimeslots
 * @property-read int|null $sig_timeslots_count
 * @property-read mixed $timetable_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TimetableEntry> $timetableEntries
 * @property-read int|null $timetable_entries_count
 * @method static \Database\Factories\SigEventFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent public()
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent query()
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent unprocessed()
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent whereAdditionalInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent whereApproval($value)
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
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent whereNoText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent whereRegPossible($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent whereRequirements($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent whereSigHostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent whereTextConfirmed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigEvent whereUpdatedAt($value)
 */
	class SigEvent extends \Eloquent {}
}

namespace App\Models{
/**
 * 
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
 * 
 *
 * @property int $id
 * @property int $sig_form_id
 * @property int $user_id
 * @property \App\Enums\Approval $approval
 * @property string|null $rejection_reason
 * @property array|null $form_data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\SigForm $sigForm
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|SigFilledForm newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigFilledForm newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigFilledForm query()
 * @method static \Illuminate\Database\Eloquent\Builder|SigFilledForm whereApproval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigFilledForm whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigFilledForm whereFormData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigFilledForm whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigFilledForm whereRejectionReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigFilledForm whereSigFormId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigFilledForm whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigFilledForm whereUserId($value)
 */
	class SigFilledForm extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property string $name_en
 * @property array|null $form_definition
 * @property int $form_closed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $name_localized
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SigEvent> $sigEvents
 * @property-read int|null $sig_events_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SigFilledForm> $sigFilledForms
 * @property-read int|null $sig_filled_forms_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserRole> $userRoles
 * @property-read int|null $user_roles_count
 * @method static \Illuminate\Database\Eloquent\Builder|SigForm newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigForm newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigForm query()
 * @method static \Illuminate\Database\Eloquent\Builder|SigForm whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigForm whereFormClosed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigForm whereFormDefinition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigForm whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigForm whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigForm whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigForm whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigForm whereUpdatedAt($value)
 */
	class SigForm extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $sig_form_id
 * @property int $user_role_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\SigForm $sigForm
 * @property-read \App\Models\UserRole $userRole
 * @method static \Illuminate\Database\Eloquent\Builder|SigFormUserRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigFormUserRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigFormUserRole query()
 * @method static \Illuminate\Database\Eloquent\Builder|SigFormUserRole whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigFormUserRole whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigFormUserRole whereSigFormId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigFormUserRole whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigFormUserRole whereUserRoleId($value)
 */
	class SigFormUserRole extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property int|null $reg_id
 * @property string|null $description
 * @property string|null $description_en
 * @property bool $hide
 * @property string|null $color
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
 * @method static \Illuminate\Database\Eloquent\Builder|SigHost whereColor($value)
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
 * 
 *
 * @property int $id
 * @property string $name
 * @property-read string|null $name_en
 * @property-read string|null $description
 * @property-read string|null $description_en
 * @property string|null $floor
 * @property string|null $room
 * @property string|null $roomsize
 * @property string|null $seats
 * @property array|null $render_ids layer id for displaying as interactive SVG or whatever
 * @property bool $infodisplay Is there a digital display in front of the door? (Signage)
 * @property bool $essential true = show periodically on the info screens (signage)
 * @property string|null $essential_description
 * @property string|null $essential_description_en
 * @property bool $show_default Show in calendar view (resource view) by default?
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ddas\Dealer> $dealers
 * @property-read int|null $dealers_count
 * @property-read mixed $description_localized
 * @property-read mixed $name_localized
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SigEvent> $sigEvents
 * @property-read int|null $sig_events_count
 * @property-read mixed $slug
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TimetableEntry> $timetableEntries
 * @property-read int|null $timetable_entries_count
 * @method static \Database\Factories\SigLocationFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocation query()
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocation used()
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocation whereDescriptionEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocation whereEssential($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocation whereEssentialDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocation whereEssentialDescriptionEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocation whereFloor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocation whereInfodisplay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigLocation whereNameEn($value)
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
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property int $timetable_entry_id
 * @property int $send_at
 * @property int $minutes_before
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
 * @method static \Illuminate\Database\Eloquent\Builder|SigReminder whereMinutesBefore($value)
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
 * 
 *
 * @property int $id
 * @property string $name Internal name, used for internal automation (eg. 'signup')
 * @property string $description
 * @property string $description_en
 * @property string|null $icon
 * @property-read string $description_localized
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SigEvent> $sigEvents
 * @property-read int|null $sig_events_count
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
 * 
 *
 * @property int $id
 * @property int $timetable_entry_id
 * @property int $max_users
 * @property \Illuminate\Support\Carbon $slot_start
 * @property \Illuminate\Support\Carbon $slot_end
 * @property \Illuminate\Support\Carbon|null $reg_start
 * @property \Illuminate\Support\Carbon|null $reg_end
 * @property string|null $description
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $self_register
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
 * @method static \Illuminate\Database\Eloquent\Builder|SigTimeslot whereSelfRegister($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigTimeslot whereSlotEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigTimeslot whereSlotStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigTimeslot whereTimetableEntryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SigTimeslot whereUpdatedAt($value)
 */
	class SigTimeslot extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property int $timeslot_id
 * @property int $send_at
 * @property int $minutes_before
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
 * 
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
 * 
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Post\Post> $posts
 * @property-read int|null $posts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SigReminder> $reminders
 * @property-read int|null $reminders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserRole> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SigFilledForm> $sigFilledForms
 * @property-read int|null $sig_filled_forms_count
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
 * 
 *
 * @property int $id
 * @property string $title
 * @property string $fore_color
 * @property string $border_color
 * @property string $background_color
 * @property string|null $registration_system_key
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DepartmentInfo> $departmentInfos
 * @property-read int|null $department_infos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserRolePermission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereBackgroundColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereBorderColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereForeColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereRegistrationSystemKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereTitle($value)
 */
	class UserRole extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_role_id
 * @property \App\Enums\Permission $permission
 * @property \App\Enums\PermissionLevel $level
 * @property-read \App\Models\UserRole|null $role
 * @method static \Illuminate\Database\Eloquent\Builder|UserRolePermission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRolePermission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRolePermission query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRolePermission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRolePermission whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRolePermission wherePermission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRolePermission whereUserRoleId($value)
 */
	class UserRolePermission extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property int $user_role_id
 * @property-read \App\Models\UserRole|null $role
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserUserRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserUserRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserUserRole query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserUserRole whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserUserRole whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserUserRole whereUserRoleId($value)
 */
	class UserUserRole extends \Eloquent {}
}

