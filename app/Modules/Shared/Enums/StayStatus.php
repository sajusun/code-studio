<?php

namespace App\Modules\Shared\Enums;

enum StayStatus: string
{
    case Scheduled = 'scheduled';
    case CheckedIn = 'checked_in';
    case CheckedOut = 'checked_out';
    case Cancelled = 'cancelled';
}
