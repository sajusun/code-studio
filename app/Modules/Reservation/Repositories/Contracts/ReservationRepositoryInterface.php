<?php

namespace App\Modules\Reservation\Repositories\Contracts;

use App\Modules\Reservation\Models\Reservation;
use Carbon\CarbonInterface;

interface ReservationRepositoryInterface
{
    public function findById(int $id): ?Reservation;

    public function findByIdOrFail(int $id): Reservation;

    public function create(array $attributes): Reservation;

    public function hasOverlappingBooking(
        int $roomId,
        CarbonInterface $checkIn,
        CarbonInterface $checkOut,
        ?int $excludeReservationId = null,
    ): bool;
}
