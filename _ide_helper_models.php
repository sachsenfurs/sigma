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


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $subject
 * @property int $user_role_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message> $messages
 * @property-read int|null $messages_count
 * @property-read mixed $unread_messages_count
 * @property-read \App\Models\User $user
 * @property-read \App\Models\UserRole $userRole
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat whereUserRoleId($value)
 */
	class Chat extends \Eloquent {}
}

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
 * @property-read \App\Models\Ddas\TFactory|null $use_factory
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\Ddas\ArtshowArtistFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowArtist havingApprovedItems()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowArtist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowArtist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowArtist query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowArtist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowArtist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowArtist whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowArtist whereSocial($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowArtist whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowArtist whereUserId($value)
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowBid newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowBid newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowBid query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowBid whereArtshowItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowBid whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowBid whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowBid whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowBid whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowBid whereValue($value)
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
 * @property-read mixed $description_localized
 * @property-read \App\Models\Ddas\TFactory|null $use_factory
 * @property-read \App\Models\Ddas\ArtshowBid|null $highestBid
 * @property-read mixed $image_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ddas\ArtshowBid> $latestBids
 * @property-read int|null $latest_bids_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowItem approvedItems()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowItem auctionableItems()
 * @method static \Database\Factories\Ddas\ArtshowItemFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowItem own()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowItem whereAdditionalInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowItem whereApproval($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowItem whereArtshowArtistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowItem whereAuction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowItem whereCharityPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowItem whereDescriptionEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowItem whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowItem whereLocked($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowItem whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowItem wherePaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowItem whereSold($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowItem whereStartingBid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowItem whereUpdatedAt($value)
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowPickup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowPickup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowPickup query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowPickup whereArtshowItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowPickup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowPickup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowPickup whereInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowPickup whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtshowPickup whereUserId($value)
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
 * @property-read \App\Models\Ddas\TFactory|null $use_factory
 * @property-read mixed $icon_file_url
 * @property-read mixed $info_localized
 * @property-read \App\Models\SigLocation|null $sigLocation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ddas\DealerTag> $tags
 * @property-read int|null $tags_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dealer approved()
 * @method static \Database\Factories\Ddas\DealerFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dealer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dealer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dealer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dealer whereAdditionalInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dealer whereApproval($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dealer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dealer whereGalleryLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dealer whereIconFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dealer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dealer whereInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dealer whereInfoEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dealer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dealer whereSigLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dealer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dealer whereUserId($value)
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DealerTag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DealerTag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DealerTag query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DealerTag used()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DealerTag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DealerTag whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DealerTag whereNameEn($value)
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepartmentInfo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepartmentInfo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepartmentInfo query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepartmentInfo whereAdditionalInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepartmentInfo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepartmentInfo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepartmentInfo whereSigEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepartmentInfo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepartmentInfo whereUserRoleId($value)
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Social footerIcon()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Social footerText()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Social newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Social newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Social query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Social signage()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Social whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Social whereDescriptionEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Social whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Social whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Social whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Social whereImageEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Social whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Social whereLinkEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Social whereLinkName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Social whereLinkNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Social whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Social whereShowOn($value)
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LostFoundItem found()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LostFoundItem lost()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LostFoundItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LostFoundItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LostFoundItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LostFoundItem returned()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LostFoundItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LostFoundItem whereFoundAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LostFoundItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LostFoundItem whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LostFoundItem whereLassieId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LostFoundItem whereLostAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LostFoundItem whereReturnedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LostFoundItem whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LostFoundItem whereThumbUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LostFoundItem whereTitle($value)
 */
	class LostFoundItem extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string|null $text
 * @property int $chat_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $avatar
 * @property-read \App\Models\Chat $chat
 * @property-read mixed $time
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message unread()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereChatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereUserId($value)
 */
	class Message extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property string $id
 * @property string $type
 * @property string $notifiable_type
 * @property int $notifiable_id
 * @property array<array-key, mixed> $data
 * @property \Illuminate\Support\Carbon|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model $notifiable
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection<int, static> all($columns = ['*'])
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection<int, static> get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification read()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification unread()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereNotifiableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereNotifiableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereUpdatedAt($value)
 */
	class Notification extends \Eloquent {}
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
 * @property-read \App\Models\Post\PostChannelMessage|null $postChannelMessage
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Post\PostChannel> $channels
 * @property-read int|null $channels_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Post\PostChannelMessage> $messages
 * @property-read int|null $messages_count
 * @property-read mixed $text_localized
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post recent()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereTextEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post withoutTrashed()
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostChannel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostChannel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostChannel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostChannel whereChannelIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostChannel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostChannel whereImplementation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostChannel whereInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostChannel whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostChannel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostChannel whereTestChannelIdentifier($value)
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostChannelMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostChannelMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostChannelMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostChannelMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostChannelMessage whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostChannelMessage wherePostChannelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostChannelMessage wherePostId($value)
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
 * @property int $timeslot_owner
 * @property-read \App\Models\SigTimeslot $sigTimeslot
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigAttendee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigAttendee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigAttendee query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigAttendee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigAttendee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigAttendee whereSigTimeslotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigAttendee whereTimeslotOwner($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigAttendee whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigAttendee whereUserId($value)
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
 * @property array<array-key, mixed> $languages two letter language code as JSON array
 * @property string|null $description
 * @property string|null $description_en
 * @property int $text_confirmed Proofreading status
 * @property int $no_text Specifies if event description is mandatory
 * @property int $duration
 * @property \App\Enums\Approval $approval 0 => Pending, 1 => Approved, 2 => Rejected
 * @property string|null $additional_info
 * @property array<array-key, mixed> $attributes
 * @property string|null $requirements
 * @property int $reg_possible
 * @property int $max_regs_per_day
 * @property int $max_group_attendees_count
 * @property array<array-key, mixed>|null $private_group_ids
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $group_registration_enabled
 * @property-read mixed $approved
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DepartmentInfo> $departmentInfos
 * @property-read int|null $department_infos_count
 * @property-read mixed $description_localized
 * @property-read mixed $description_localized_other
 * @property-read mixed $duration_hours
 * @property-read mixed $favorite_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SigForm> $forms
 * @property-read int|null $forms_count
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read mixed $is_private
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigEvent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigEvent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigEvent public()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigEvent query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigEvent unprocessed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigEvent whereAdditionalInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigEvent whereApproval($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigEvent whereAttributes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigEvent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigEvent whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigEvent whereDescriptionEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigEvent whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigEvent whereGroupRegistrationEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigEvent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigEvent whereLanguages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigEvent whereMaxGroupAttendeesCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigEvent whereMaxRegsPerDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigEvent whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigEvent whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigEvent whereNoText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigEvent wherePrivateGroupIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigEvent whereRegPossible($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigEvent whereRequirements($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigEvent whereSigHostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigEvent whereTextConfirmed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigEvent whereUpdatedAt($value)
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigFavorite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigFavorite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigFavorite query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigFavorite upcoming()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigFavorite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigFavorite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigFavorite whereTimetableEntryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigFavorite whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigFavorite whereUserId($value)
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
 * @property array<array-key, mixed>|null $form_data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\SigForm $sigForm
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigFilledForm newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigFilledForm newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigFilledForm query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigFilledForm whereApproval($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigFilledForm whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigFilledForm whereFormData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigFilledForm whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigFilledForm whereRejectionReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigFilledForm whereSigFormId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigFilledForm whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigFilledForm whereUserId($value)
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
 * @property array<array-key, mixed>|null $form_definition
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigForm newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigForm newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigForm query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigForm whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigForm whereFormClosed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigForm whereFormDefinition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigForm whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigForm whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigForm whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigForm whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigForm whereUpdatedAt($value)
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigFormUserRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigFormUserRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigFormUserRole query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigFormUserRole whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigFormUserRole whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigFormUserRole whereSigFormId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigFormUserRole whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigFormUserRole whereUserRoleId($value)
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
 * @property-read mixed $avatar_thumb
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read mixed $public_sig_event_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SigEvent> $sigEvents
 * @property-read int|null $sig_events_count
 * @property-read mixed $slug
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TimetableEntry> $timetableEntries
 * @property-read int|null $timetable_entries_count
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\SigHostFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigHost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigHost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigHost public()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigHost query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigHost whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigHost whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigHost whereDescriptionEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigHost whereHide($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigHost whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigHost whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigHost whereRegId($value)
 */
	class SigHost extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $name_en
 * @property string|null $description
 * @property string|null $description_en
 * @property string|null $floor
 * @property string|null $room
 * @property string|null $roomsize
 * @property string|null $seats
 * @property array<array-key, mixed>|null $render_ids layer id for displaying as interactive SVG or whatever
 * @property bool $infodisplay Is there a digital display in front of the door? (Signage)
 * @property bool $essential true = show periodically on the info screens (signage)
 * @property string|null $essential_description
 * @property string|null $essential_description_en
 * @property bool $show_default Show in calendar view (resource view) by default?
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ddas\Dealer> $dealers
 * @property-read int|null $dealers_count
 * @property-read mixed $description_localized
 * @property-read mixed $essential_description_localized
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read mixed $name_localized
 * @property-read mixed $public_sig_event_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SigEvent> $sigEvents
 * @property-read int|null $sig_events_count
 * @property-read mixed $slug
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TimetableEntry> $timetableEntries
 * @property-read int|null $timetable_entries_count
 * @method static \Database\Factories\SigLocationFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigLocation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigLocation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigLocation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigLocation used()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigLocation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigLocation whereDescriptionEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigLocation whereEssential($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigLocation whereEssentialDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigLocation whereEssentialDescriptionEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigLocation whereFloor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigLocation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigLocation whereInfodisplay($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigLocation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigLocation whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigLocation whereRenderIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigLocation whereRoom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigLocation whereRoomsize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigLocation whereSeats($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigLocation whereShowDefault($value)
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigReminder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigReminder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigReminder query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigReminder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigReminder whereExecutedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigReminder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigReminder whereMinutesBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigReminder whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigReminder whereSendAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigReminder whereTimetableEntryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigReminder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigReminder whereUserId($value)
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigTag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigTag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigTag query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigTag whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigTag whereDescriptionEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigTag whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigTag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigTag whereName($value)
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigTimeslot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigTimeslot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigTimeslot query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigTimeslot whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigTimeslot whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigTimeslot whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigTimeslot whereMaxUsers($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigTimeslot whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigTimeslot whereRegEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigTimeslot whereRegStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigTimeslot whereSelfRegister($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigTimeslot whereSlotEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigTimeslot whereSlotStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigTimeslot whereTimetableEntryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigTimeslot whereUpdatedAt($value)
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigTimeslotReminder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigTimeslotReminder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigTimeslotReminder query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigTimeslotReminder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigTimeslotReminder whereExecutedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigTimeslotReminder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigTimeslotReminder whereMinutesBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigTimeslotReminder whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigTimeslotReminder whereSendAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigTimeslotReminder whereTimeslotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigTimeslotReminder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SigTimeslotReminder whereUserId($value)
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
 * @property-read mixed $event_color
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SigFavorite> $favorites
 * @property-read int|null $favorites_count
 * @property-read mixed $formatted_length
 * @property-read bool $has_location_changed
 * @property-read bool $has_time_changed
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read mixed $is_favorite
 * @property-read TimetableEntry|null $parentEntry
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SigReminder> $reminders
 * @property-read int|null $reminders_count
 * @property-read TimetableEntry|null $replacedBy
 * @property-read \App\Models\SigEvent $sigEvent
 * @property-read \App\Models\SigLocation $sigLocation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SigTimeslot> $sigTimeslots
 * @property-read int|null $sig_timeslots_count
 * @method static \Database\Factories\TimetableEntryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableEntry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableEntry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableEntry noAnnouncements()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableEntry public()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableEntry query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableEntry whereCancelled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableEntry whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableEntry whereEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableEntry whereHide($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableEntry whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableEntry whereNew($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableEntry whereReplacedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableEntry whereSigEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableEntry whereSigLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableEntry whereStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableEntry whereUpdatedAt($value)
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
 * @property int $checkedin
 * @property string|null $language
 * @property int|null $telegram_id
 * @property array<array-key, mixed> $groups
 * @property string|null $avatar
 * @property string|null $avatar_thumb
 * @property array<array-key, mixed> $notification_channels
 * @property string|null $telegram_user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ddas\ArtshowArtist> $artists
 * @property-read int|null $artists_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ddas\ArtshowBid> $artshowBids
 * @property-read int|null $artshow_bids_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SigAttendee> $attendeeEvents
 * @property-read int|null $attendee_events_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Chat> $chats
 * @property-read int|null $chats_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ddas\Dealer> $dealers
 * @property-read int|null $dealers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SigFavorite> $favorites
 * @property-read int|null $favorites_count
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \App\Models\Notification> $notifications
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
 * @property-read mixed $unread_messages_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAvatarThumb($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCheckedin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereGroups($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereNotificationChannels($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRegId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTelegramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTelegramUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent implements \Filament\Models\Contracts\FilamentUser, \Filament\Models\Contracts\HasAvatar, \Illuminate\Contracts\Translation\HasLocalePreference {}
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
 * @property int $chat_activated
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Chat> $chats
 * @property-read int|null $chats_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DepartmentInfo> $departmentInfos
 * @property-read int|null $department_infos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserRolePermission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserRole chattable()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserRole query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserRole whereBackgroundColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserRole whereBorderColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserRole whereChatActivated($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserRole whereForeColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserRole whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserRole whereRegistrationSystemKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserRole whereTitle($value)
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserRolePermission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserRolePermission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserRolePermission query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserRolePermission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserRolePermission whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserRolePermission wherePermission($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserRolePermission whereUserRoleId($value)
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
 * @property int $new_chat_notifications
 * @property-read \App\Models\UserRole|null $role
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserUserRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserUserRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserUserRole query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserUserRole whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserUserRole whereNewChatNotifications($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserUserRole whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserUserRole whereUserRoleId($value)
 */
	class UserUserRole extends \Eloquent {}
}

