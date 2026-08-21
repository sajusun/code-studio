<?php

namespace App\Modules\Reservation\DTOs;

use Carbon\CarbonInterface;

readonly class AvailabilitySearchData
{
    public function __construct(
        public CarbonInterface $checkInDate,
        public CarbonInterface $checkOutDate,
        public ?int $roomTypeId = null,
        public int $guestsCount = 1,
    ) {}
}
