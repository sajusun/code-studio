<?php

namespace App\Modules\Shared\Enums;

enum RoomStatus: string
{
    case Available = 'available';
    case Reserved = 'reserved';
    case Occupied = 'occupied';
    case Maintenance = 'maintenance';
}
