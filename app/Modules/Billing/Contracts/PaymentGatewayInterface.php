<?php

namespace App\Modules\Billing\Contracts;

use App\Modules\Billing\DTOs\PaymentSessionData;
use App\Modules\Billing\DTOs\WebhookResult;
use App\Modules\Billing\Models\Invoice;
use App\Modules\Reservation\Models\Reservation;

/**
 * Defines the contract for all hosted payment gateways.
 * To add a new gateway (e.g. Razorpay), simply implement this interface.
 */
interface PaymentGatewayInterface
{
    /**
     * Create a hosted payment session and return the redirect URL.
     */
    public function createSession(Invoice|Reservation $model, float $amount, string $currency = 'USD'): PaymentSessionData;

    /**
     * Verify and parse an incoming webhook payload.
     * Returns the gateway transaction reference on success, throws on failure.
     */
    public function handleWebhook(string $payload, array $headers): ?WebhookResult;

    /**
     * Returns the gateway identifier (e.g. 'stripe', 'paypal').
     */
    public function getName(): string;
}
