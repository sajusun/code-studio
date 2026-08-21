<?php

namespace App\Modules\Billing\DTOs;

/**
 * Data returned after creating a hosted payment session.
 */
readonly class PaymentSessionData
{
    public function __construct(
        /** The URL to redirect the guest/admin to for payment */
        public string $redirectUrl,
        /** The gateway-specific session or order ID */
        public string $sessionId,
        /** e.g. 'stripe' or 'paypal' */
        public string $gateway,
    ) {}
}
