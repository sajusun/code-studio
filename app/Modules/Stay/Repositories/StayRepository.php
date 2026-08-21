<?php

namespace App\Modules\Stay\Repositories;

use App\Modules\Stay\Models\Stay;
use App\Modules\Stay\Repositories\Contracts\StayRepositoryInterface;

class StayRepository implements StayRepositoryInterface
{
    public function findByIdOrFail(int $id): Stay
    {
        return Stay::query()
            ->with(['reservation', 'room.roomType', 'guest', 'invoice.items', 'invoice.payments'])
            ->findOrFail($id);
    }

    public function create(array $attributes): Stay
    {
        return Stay::query()->create($attributes);
    }

    public function update(int $id, array $attributes): Stay
    {
        $stay = $this->findByIdOrFail($id);
        $stay->update($attributes);

        return $stay->fresh([
            'reservation',
            'room.roomType',
            'guest',
            'invoice.items',
            'invoice.payments',
        ]);
    }
}
