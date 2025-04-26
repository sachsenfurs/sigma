<?php

namespace App\Services;

use App\Enums\Approval;
use App\Enums\MessageRecipient;
use App\Models\Ddas\ArtshowArtist;
use App\Models\Ddas\ArtshowItem;
use App\Models\Ddas\Dealer;
use App\Models\SigHost;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class MessageRecipientService
{
    public static function getRecipients(MessageRecipient $type, ?Model $instance=null): Collection {
        return (match($type) {
            MessageRecipient::USER_ROLE => $instance->users,
            MessageRecipient::HOST => SigHost::with("user")->whereHas("sigEvents")->get()->pluck("user"),
            MessageRecipient::FILLED_FORM_USER => $instance->sigFilledForms()->with("user")->get()->pluck("user"),
            MessageRecipient::FILLED_FORM_USER_APPROVED => $instance->sigFilledForms()->where("approval", Approval::APPROVED)->with("user")->get()->pluck("user"),
            MessageRecipient::FILLED_FORM_USER_REJECTED => $instance->sigFilledForms()->where("approval", Approval::REJECTED)->with("user")->get()->pluck("user"),
            MessageRecipient::DEALER => Dealer::with("user")->get()->pluck("user"),
            MessageRecipient::DEALER_APPROVED => Dealer::approved()->with("user")->get()->pluck("user"),
            MessageRecipient::DEALER_REJECTED => Dealer::where("approval", Approval::REJECTED)->with("user")->get()->pluck("user"),
            MessageRecipient::ARTIST => ArtshowArtist::with("user")->get()->pluck("user"),
            MessageRecipient::ARTSHOW_WINNER => ArtshowItem::with("highestBid")->get()->pluck("highestBid.user"),
            MessageRecipient::TIMETABLE_ENTRY_ATTENDEE => $instance->sigTimeslots()->with("sigAttendees.user")->get()->map(fn($e) => $e->sigAttendees->pluck("user"))->flatten(),
            MessageRecipient::TIMETABLE_ENTRY_FAVED => $instance->favorites()->with("user")->get()->pluck("user"),
            default => collect(),
        })->whereNotNull();
    }
}
