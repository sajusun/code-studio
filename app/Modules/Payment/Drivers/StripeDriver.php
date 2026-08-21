<?php

namespace App\Modules\Payment\Drivers;

use App\Models\Payment;
use App\Modules\Payment\Contracts\PaymentGatewayInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeDriver implements PaymentGatewayInterface
{
    public function __construct()
    {
        Stripe::setApiKey(config('payment.drivers.stripe.secret'));
    }

    public function charge(array $payload): array
    {
        $transactionId = 'STRIPE_' . strtoupper(Str::random(12));
        $amount = (float) $payload['amount'];
        $currency = strtolower($payload['currency'] ?? config('payment.currency', 'USD'));

        $successUrl = $payload['return_url'] ?? url(config('payment.success_url')) . "?session_id={CHECKOUT_SESSION_ID}&tx={$transactionId}";
        $cancelUrl = url(config('payment.cancel_url')) . "?tx={$transactionId}";

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => $currency,
                    'product_data' => [
                        'name' => $payload['description'] ?? 'Payment Checkout',
                    ],
                    'unit_amount' => (int) ($amount * 100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'client_reference_id' => $transactionId,
        ]);

        $payment = Payment::create([
            'user_id' => $payload['user_id'] ?? auth()->id(),
            'transaction_id' => $transactionId,
            'provider' => 'stripe',
            'amount' => $amount,
            'currency' => strtoupper($currency),
            'status' => 'pending',
            'payment_method' => 'card',
            'raw_response' => ['session_id' => $session->id],
        ]);

        return [
            'redirect_url' => $session->url,
            'transaction_id' => $transactionId,
            'session_id' => $session->id,
            'payment' => $payment,
        ];
    }

    public function verify(string $transactionId): Payment
    {
        $payment = Payment::where('transaction_id', $transactionId)->firstOrFail();
        // Return existing payment or sync with Stripe Session if needed
        return $payment;
    }

    public function handleWebhook(Request $request): array
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('payment.drivers.stripe.webhook_secret');

        try {
            if ($endpointSecret && $sigHeader) {
                $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
            } else {
                $event = json_decode($payload, false);
            }

            if ($event->type === 'checkout.session.completed') {
                $session = $event->data->object;
                $transactionId = $session->client_reference_id;

                $payment = Payment::where('transaction_id', $transactionId)->first();
                if ($payment) {
                    $payment->update([
                        'status' => 'completed',
                        'raw_response' => (array) $session,
                    ]);
                }
            }

            return ['status' => 'success'];
        } catch (\Throwable $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}
