<?php

namespace App\Modules\Stay\Repositories\Contracts;

use App\Modules\Stay\Models\Stay;

interface StayRepositoryInterface
{
    public function findByIdOrFail(int $id): Stay;

    public function create(array $attributes): Stay;

    public function update(int $id, array $attributes): Stay;
}
