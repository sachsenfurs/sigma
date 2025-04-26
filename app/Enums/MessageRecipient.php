<?php

namespace App\Enums;

enum MessageRecipient
{
    case USER_ROLE; // Department
    case FILLED_FORM_USER; // user who has filled out a specific sig_form
    case FILLED_FORM_USER_APPROVED; // ... and was appproved
    case FILLED_FORM_USER_REJECTED; // ... and was rejected
    case DEALER;
    case DEALER_APPROVED;
    case DEALER_REJECTED;
    case ARTIST;
    case ARTSHOW_WINNER;
    case HOST;
    case TIMETABLE_ENTRY_ATTENDEE;
    case TIMETABLE_ENTRY_FAVED; // user who faved a specific sig
}
