<?php

namespace App\Modules\Shared\Enums;

enum ReservationStatus: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Cancelled = 'cancelled';
    case Completed = 'completed';
}
