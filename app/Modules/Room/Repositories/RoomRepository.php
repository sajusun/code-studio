<?php

namespace App\Modules\Room\Repositories;

use App\Modules\Reservation\Models\Reservation;
use App\Modules\Room\Models\Room;
use App\Modules\Room\Repositories\Contracts\RoomRepositoryInterface;
use App\Modules\Shared\Enums\ReservationStatus;
use App\Modules\Shared\Enums\RoomStatus;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

class RoomRepository implements RoomRepositoryInterface
{
    public function findById(int $id): ?Room
    {
        return Room::query()->with('roomType')->find($id);
    }

    public function findByIdOrFail(int $id): Room
    {
        return Room::query()->with('roomType')->findOrFail($id);
    }

    public function updateStatus(int $roomId, RoomStatus $status): Room
    {
        $room = $this->findByIdOrFail($roomId);
        $room->update(['status' => $status]);

        return $room->fresh(['roomType']);
    }

    public function findAvailableForDateRange(
        CarbonInterface $checkIn,
        CarbonInterface $checkOut,
        ?int $roomTypeId = null,
        ?int $excludeReservationId = null,
    ): Collection {
        $conflictingRoomIds = Reservation::query()
            ->whereIn('status', [
                ReservationStatus::Pending,
                ReservationStatus::Confirmed,
            ])
            ->whereDate('check_in_date', '<', $checkOut)
            ->whereDate('check_out_date', '>', $checkIn)
            ->when($excludeReservationId, fn ($q) => $q->where('id', '!=', $excludeReservationId))
            ->pluck('room_id');

        return Room::query()
            ->with('roomType')
            ->where('status', '!=', RoomStatus::Maintenance)
            ->when($roomTypeId, fn ($q) => $q->where('room_type_id', $roomTypeId))
            ->whereNotIn('id', $conflictingRoomIds)
            ->orderBy('number')
            ->get();
    }

    public function create(array $attributes): Room
    {
        return Room::query()->create($attributes);
    }

    public function update(int $id, array $attributes): Room
    {
        $room = $this->findByIdOrFail($id);
        $room->update($attributes);

        return $room->fresh(['roomType']);
    }

    public function delete(int $id): bool
    {
        $room = $this->findByIdOrFail($id);
        return $room->delete();
    }
}
