<?php

namespace App\Modules\Billing\DTOs;

/**
 * Parsed result from a gateway webhook event.
 */
readonly class WebhookResult
{
    public function __construct(
        public string $gateway,
        public ?string $invoiceId,
        public string $transactionReference,
        public float $amount,
        public string $status, // 'completed' | 'failed' | 'refunded'
        public ?string $reservationId = null,
    ) {}
}
