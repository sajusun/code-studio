<?php

namespace App\Modules\Billing\Gateways;

use App\Modules\Billing\Contracts\PaymentGatewayInterface;
use App\Modules\Billing\DTOs\PaymentSessionData;
use App\Modules\Billing\DTOs\WebhookResult;
use App\Modules\Billing\Models\Invoice;
use App\Modules\Reservation\Models\Reservation;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeGateway implements PaymentGatewayInterface
{
    public function __construct()
    {
        Stripe::setApiKey(config('payment.stripe.secret'));
    }

    public function getName(): string
    {
        return 'stripe';
    }

    /**
     * Creates a Stripe Checkout hosted session and returns the redirect URL.
     */
    public function createSession(Invoice|Reservation $model, float $amount, string $currency = 'USD'): PaymentSessionData
    {
        $model->loadMissing('guest');
        $email = $model->guest?->email;

        $metadata = [];
        if ($model instanceof Invoice) {
            $name = "Invoice {$model->invoice_number}";
            $description = "Payment for stay — {$model->nights} night(s)";
            $metadata['invoice_id'] = $model->id;
            $metadata['invoice_number'] = $model->invoice_number;
            $successUrl = config('payment.success_url') . "?session_id={CHECKOUT_SESSION_ID}&gateway=stripe&invoice_id={$model->id}";
            $cancelUrl = config('payment.cancel_url') . "?gateway=stripe&invoice_id={$model->id}";
        } else {
            $name = "Reservation {$model->reference}";
            $description = "Online booking reservation payment";
            $metadata['reservation_id'] = $model->id;
            $metadata['reservation_reference'] = $model->reference;
            $successUrl = config('payment.success_url') . "?session_id={CHECKOUT_SESSION_ID}&gateway=stripe&reservation_id={$model->id}";
            $cancelUrl = config('payment.cancel_url') . "?gateway=stripe&reservation_id={$model->id}";
        }

        $session = Session::create([
            'payment_method_types' => ['card'],
            'mode'                 => 'payment',
            'customer_email'       => $email,
            'line_items'           => [
                [
                    'price_data' => [
                        'currency'     => strtolower($currency),
                        'unit_amount'  => (int) round($amount * 100), // cents
                        'product_data' => [
                            'name'        => $name,
                            'description' => $description,
                        ],
                    ],
                    'quantity' => 1,
                ],
            ],
            'metadata'    => $metadata,
            'success_url' => $successUrl,
            'cancel_url'  => $cancelUrl,
        ]);

        return new PaymentSessionData(
            redirectUrl: $session->url,
            sessionId: $session->id,
            gateway: $this->getName(),
        );
    }

    /**
     * Verify the Stripe webhook signature and extract payment data.
     */
    public function handleWebhook(string $payload, array $headers): ?WebhookResult
    {
        $secret = config('payment.stripe.webhook_secret');
        $sigHeader = $headers['stripe-signature'] ?? '';

        $event = Webhook::constructEvent($payload, $sigHeader, $secret);

        if ($event->type !== 'checkout.session.completed') {
            return null;
        }

        /** @var \Stripe\Checkout\Session $session */
        $session = $event->data->object;

        if ($session->payment_status !== 'paid') {
            return null;
        }

        return new WebhookResult(
            gateway: $this->getName(),
            invoiceId: $session->metadata->invoice_id ?? null,
            transactionReference: $session->payment_intent ?? $session->id,
            amount: $session->amount_total / 100,
            status: 'completed',
            reservationId: $session->metadata->reservation_id ?? null,
        );
    }
}
