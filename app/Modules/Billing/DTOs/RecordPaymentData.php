<?php

namespace App\Modules\Billing\DTOs;

readonly class RecordPaymentData
{
    public function __construct(
        public int $invoiceId,
        public float $amount,
        public string $method,
        public ?string $transactionReference = null,
    ) {}
}
