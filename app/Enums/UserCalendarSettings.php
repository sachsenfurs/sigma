<?php

namespace App\Enums;

use App\Enums\Attributes\Name;
use App\Enums\Traits\AttributableEnum;

enum UserCalendarSettings
{
    use AttributableEnum;

    #[Name('Show Events')]
    case SHOW_EVENTS;

    #[Name('Show Favorites')]
    case SHOW_FAVORITES;

    #[Name('Show Time Slots')]
    case SHOW_TIMESLOTS;

    #[Name("Show Shifts")]
    case SHOW_SHIFTS;
}
