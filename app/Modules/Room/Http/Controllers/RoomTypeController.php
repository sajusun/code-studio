<?php

namespace App\Modules\Room\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Room\Http\Requests\StoreRoomTypeRequest;
use App\Modules\Room\Http\Requests\UpdateRoomTypeRequest;
use App\Modules\Room\Http\Resources\RoomTypeResource;
use App\Modules\Room\Repositories\Contracts\RoomTypeRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RoomTypeController extends Controller
{
    public function __construct(
        private readonly RoomTypeRepositoryInterface $roomTypes,
    ) {}

    public function index(): AnonymousResourceCollection
    {
        return RoomTypeResource::collection(
            \App\Modules\Room\Models\RoomType::query()->withCount('rooms')->orderBy('name')->get()
        );
    }

    public function store(StoreRoomTypeRequest $request): RoomTypeResource
    {
        $roomType = $this->roomTypes->create($request->validated());
        return new RoomTypeResource($roomType);
    }

    public function show(int $roomType): RoomTypeResource
    {
        return new RoomTypeResource($this->roomTypes->findByIdOrFail($roomType));
    }

    public function update(UpdateRoomTypeRequest $request, int $roomType): RoomTypeResource
    {
        $updated = $this->roomTypes->update($roomType, $request->validated());
        return new RoomTypeResource($updated);
    }

    public function destroy(int $roomType): JsonResponse
    {
        $this->roomTypes->delete($roomType);
        return response()->json(['message' => 'Room type deleted successfully']);
    }
}
