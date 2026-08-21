<?php

namespace App\Modules\Room\Repositories\Contracts;

use App\Modules\Room\Models\Room;
use App\Modules\Shared\Enums\RoomStatus;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

interface RoomRepositoryInterface
{
    public function findById(int $id): ?Room;

    public function findByIdOrFail(int $id): Room;

    public function updateStatus(int $roomId, RoomStatus $status): Room;

    /**
     * @return Collection<int, Room>
     */
    public function findAvailableForDateRange(
        CarbonInterface $checkIn,
        CarbonInterface $checkOut,
        ?int $roomTypeId = null,
        ?int $excludeReservationId = null,
    ): Collection;

    public function create(array $attributes): Room;

    public function update(int $id, array $attributes): Room;

    public function delete(int $id): bool;
}
