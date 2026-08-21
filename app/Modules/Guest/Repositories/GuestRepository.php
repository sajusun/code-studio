<?php

namespace App\Modules\Guest\Repositories;

use App\Modules\Guest\Models\Guest;
use App\Modules\Guest\Repositories\Contracts\GuestRepositoryInterface;

class GuestRepository implements GuestRepositoryInterface
{
    public function findByIdOrFail(int $id): Guest
    {
        return Guest::query()->findOrFail($id);
    }

    public function create(array $attributes): Guest
    {
        return Guest::query()->create($attributes);
    }

    public function update(int $id, array $attributes): Guest
    {
        $guest = $this->findByIdOrFail($id);
        $guest->update($attributes);

        return $guest->fresh();
    }
}
