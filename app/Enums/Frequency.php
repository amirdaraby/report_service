<?php

namespace App\Enums;

use App\Enums\Traits\Values;

enum Frequency: int
{
    use Values;

    case DAILY = 1;
    case WEEKLY = 2;
}
