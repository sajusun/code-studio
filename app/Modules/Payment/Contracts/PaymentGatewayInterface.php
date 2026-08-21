<?php

namespace App\Modules\Payment\Contracts;

use App\Models\Payment;
use Illuminate\Http\Request;

interface PaymentGatewayInterface
{
    /**
     * Initiate checkout or payment charge.
     *
     * @param array $payload { amount: float, currency?: string, user_id?: int, description?: string, return_url?: string }
     * @return array { redirect_url?: string, payment_id?: string, transaction_id: string, client_secret?: string }
     */
    public function charge(array $payload): array;

    /**
     * Verify payment status with the gateway.
     */
    public function verify(string $transactionId): Payment;

    /**
     * Handle incoming webhooks or IPNs.
     */
    public function handleWebhook(Request $request): array;
}
