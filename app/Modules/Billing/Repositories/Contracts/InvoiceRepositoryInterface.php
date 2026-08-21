<?php

namespace App\Modules\Billing\Repositories\Contracts;

use App\Modules\Billing\Models\Invoice;

interface InvoiceRepositoryInterface
{
    public function findByIdOrFail(int $id): Invoice;

    public function create(array $attributes): Invoice;
}
