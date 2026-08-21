<?php

namespace App\Modules\Room\Repositories\Contracts;

use App\Modules\Room\Models\RoomType;

interface RoomTypeRepositoryInterface
{
    public function findById(int $id): ?RoomType;

    public function findByIdOrFail(int $id): RoomType;

    public function create(array $attributes): RoomType;

    public function update(int $id, array $attributes): RoomType;

    public function delete(int $id): bool;
}
