<?php

namespace App\Modules\Room\Services;

use App\Modules\Room\Models\Room;
use App\Modules\Room\Models\RoomType;
use App\Modules\Room\Repositories\Contracts\RoomRepositoryInterface;
use App\Modules\Shared\Enums\RoomStatus;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class RoomService
{
    public function __construct(
        private readonly RoomRepositoryInterface $rooms,
    ) {}

    /**
     * @return LengthAwarePaginator<Room>
     */
    public function paginateRooms(int $perPage = 10, ?RoomStatus $status = null): LengthAwarePaginator
    {
        return Room::query()
            ->with('roomType')
            ->when($status, fn ($q) => $q->where('status', $status))
            ->orderBy('number')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * @return Collection<int, RoomType>
     */
    public function listRoomTypes(): Collection
    {
        return RoomType::query()->withCount('rooms')->orderBy('name')->get();
    }

    public function updateRoomStatus(int $roomId, RoomStatus $status): Room
    {
        return $this->rooms->updateStatus($roomId, $status);
    }
}
