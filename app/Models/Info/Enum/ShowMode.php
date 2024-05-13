<?php

namespace App\Models\Info\Enum;

use Illuminate\Contracts\Database\Eloquent\Castable;

enum ShowMode: int
{
    case SIGNAGE = 0;
    case FOOTER_ICON = 1;
    case FOOTER_TEXT = 2;

}
