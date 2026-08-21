<?php

namespace App\Modules\Room\Repositories;

use App\Modules\Room\Models\RoomType;
use App\Modules\Room\Repositories\Contracts\RoomTypeRepositoryInterface;

class RoomTypeRepository implements RoomTypeRepositoryInterface
{
    public function findById(int $id): ?RoomType
    {
        return RoomType::query()->find($id);
    }

    public function findByIdOrFail(int $id): RoomType
    {
        return RoomType::query()->findOrFail($id);
    }

    public function create(array $attributes): RoomType
    {
        return RoomType::query()->create($attributes);
    }

    public function update(int $id, array $attributes): RoomType
    {
        $roomType = $this->findByIdOrFail($id);
        $roomType->update($attributes);

        return $roomType->fresh();
    }

    public function delete(int $id): bool
    {
        $roomType = $this->findByIdOrFail($id);
        return $roomType->delete();
    }
}
