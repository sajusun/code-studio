<?php

namespace App\Modules\Billing\Repositories;

use App\Modules\Billing\Models\Invoice;
use App\Modules\Billing\Repositories\Contracts\InvoiceRepositoryInterface;

class InvoiceRepository implements InvoiceRepositoryInterface
{
    public function findByIdOrFail(int $id): Invoice
    {
        return Invoice::query()
            ->with(['items', 'payments', 'stay.reservation', 'guest'])
            ->findOrFail($id);
    }

    public function create(array $attributes): Invoice
    {
        return Invoice::query()->create($attributes);
    }
}
