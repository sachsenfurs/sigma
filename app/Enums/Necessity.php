<?php

namespace App\Enums;

enum Necessity: int
{
    case OPTIONAL = 0;
    case NICE_TO_HAVE = 1;
    case MANDATORY = 2;
}
