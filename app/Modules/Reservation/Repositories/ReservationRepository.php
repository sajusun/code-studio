<?php

namespace App\Modules\Reservation\Repositories;

use App\Modules\Reservation\Models\Reservation;
use App\Modules\Reservation\Repositories\Contracts\ReservationRepositoryInterface;
use App\Modules\Shared\Enums\ReservationStatus;
use Carbon\CarbonInterface;

class ReservationRepository implements ReservationRepositoryInterface
{
    public function findById(int $id): ?Reservation
    {
        return Reservation::query()
            ->with(['room.roomType', 'guest', 'stay'])
            ->find($id);
    }

    public function findByIdOrFail(int $id): Reservation
    {
        return Reservation::query()
            ->with(['room.roomType', 'guest', 'stay'])
            ->findOrFail($id);
    }

    public function create(array $attributes): Reservation
    {
        return Reservation::query()->create($attributes);
    }

    public function hasOverlappingBooking(
        int $roomId,
        CarbonInterface $checkIn,
        CarbonInterface $checkOut,
        ?int $excludeReservationId = null,
    ): bool {
        return Reservation::query()
            ->where('room_id', $roomId)
            ->whereIn('status', [
                ReservationStatus::Pending,
                ReservationStatus::Confirmed,
            ])
            ->whereDate('check_in_date', '<', $checkOut)
            ->whereDate('check_out_date', '>', $checkIn)
            ->when($excludeReservationId, fn ($q) => $q->where('id', '!=', $excludeReservationId))
            ->exists();
    }
}
