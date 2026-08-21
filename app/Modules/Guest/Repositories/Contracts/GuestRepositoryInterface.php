<?php

namespace App\Modules\Guest\Repositories\Contracts;

use App\Modules\Guest\Models\Guest;

interface GuestRepositoryInterface
{
    public function findByIdOrFail(int $id): Guest;

    public function create(array $attributes): Guest;

    public function update(int $id, array $attributes): Guest;
}
