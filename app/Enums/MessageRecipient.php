<?php

namespace App\Enums;

use App\Enums\Attributes\Name;
use App\Enums\Attributes\TargetClass;
use App\Enums\Attributes\TitleAttribute;
use App\Enums\Traits\AttributableEnum;
use App\Models\SigForm;
use App\Models\TimetableEntry;
use App\Models\UserRole;

enum MessageRecipient
{
    use AttributableEnum;

    #[Name('Department')]
    #[TargetClass(UserRole::class)]
    #[TitleAttribute('name_localized')]
    case USER_ROLE; // Department

    #[Name('Filled Form User')]
    #[TargetClass(SigForm::class)]
    #[TitleAttribute('name_localized')]
    case FILLED_FORM_USER; // user who has filled out a specific sig_form

    #[Name('Filled Form User (Approved)')]
    #[TargetClass(SigForm::class)]
    #[TitleAttribute('name_localized')]
    case FILLED_FORM_USER_APPROVED; // ... and was appproved

    #[Name('Filled Form User (Rejected)')]
    #[TargetClass(SigForm::class)]
    #[TitleAttribute('name_localized')]
    case FILLED_FORM_USER_REJECTED; // ... and was rejected

    #[Name('Dealers')]
    case DEALER;

    #[Name('Dealers (Approved)')]
    case DEALER_APPROVED;

    #[Name('Dealers (Rejected)')]
    case DEALER_REJECTED;

    #[Name('Artists')]
    case ARTIST;

    #[Name('Artshow Winner')]
    case ARTSHOW_WINNER;

    #[Name('Hosts')]
    case HOST;

    #[Name('Timeable Entry Attendee')]
    #[TargetClass(TimetableEntry::class)]
    #[TitleAttribute('detailed_name_localized')]
    case TIMETABLE_ENTRY_ATTENDEE;

    #[Name('Timeable Entry Favorited User')]
    #[TargetClass(TimetableEntry::class)]
    #[TitleAttribute('detailed_name_localized')]
    case TIMETABLE_ENTRY_FAVED; // user who faved a specific sig

    public static function strings(): array {
        $types = [];
        foreach(self::cases() as $case) {
            $types[$case->name] = __($case->name()) ?? $case->name;
        }
        return $types;
    }
}
