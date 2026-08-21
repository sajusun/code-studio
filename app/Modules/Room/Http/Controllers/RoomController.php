<?php

namespace App\Modules\Room\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Room\Http\Requests\UpdateRoomStatusRequest;
use App\Modules\Room\Http\Requests\StoreRoomRequest;
use App\Modules\Room\Http\Requests\UpdateRoomRequest;
use App\Modules\Room\Http\Resources\RoomResource;
use App\Modules\Room\Http\Resources\RoomTypeResource;
use App\Modules\Room\Repositories\Contracts\RoomRepositoryInterface;
use App\Modules\Room\Services\RoomService;
use App\Modules\Shared\Enums\RoomStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RoomController extends Controller
{
    public function __construct(
        private readonly RoomService $roomService,
        private readonly RoomRepositoryInterface $rooms,
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $status = $request->query('status')
            ? RoomStatus::from($request->query('status'))
            : null;

        $perPage = (int) $request->integer('per_page', 10);
        $perPage = max(1, min(100, $perPage));

        return RoomResource::collection($this->roomService->paginateRooms($perPage, $status));
    }

    public function roomTypes(): AnonymousResourceCollection
    {
        return RoomTypeResource::collection($this->roomService->listRoomTypes());
    }

    public function store(StoreRoomRequest $request): RoomResource
    {
        $room = $this->rooms->create($request->validated());
        return new RoomResource($room->load('roomType'));
    }

    public function show(int $room): RoomResource
    {
        return new RoomResource($this->rooms->findByIdOrFail($room));
    }

    public function update(UpdateRoomRequest $request, int $room): RoomResource
    {
        $updated = $this->rooms->update($room, $request->validated());
        return new RoomResource($updated);
    }

    public function updateStatus(UpdateRoomStatusRequest $request, int $room): RoomResource
    {
        $updated = $this->roomService->updateRoomStatus(
            $room,
            RoomStatus::from($request->validated('status')),
        );

        return new RoomResource($updated);
    }

    public function destroy(int $room): JsonResponse
    {
        $this->rooms->delete($room);
        return response()->json(['message' => 'Room deleted successfully']);
    }
}
