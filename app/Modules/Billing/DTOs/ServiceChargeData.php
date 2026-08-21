<?php

namespace App\Modules\Billing\DTOs;

readonly class ServiceChargeData
{
    public function __construct(
        public string $description,
        public float $unitPrice,
        public int $quantity = 1,
        public string $type = 'service',
    ) {}

    public function total(): float
    {
        return round($this->unitPrice * $this->quantity, 2);
    }
}
