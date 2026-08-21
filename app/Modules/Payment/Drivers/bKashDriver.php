<?php

namespace App\Modules\Payment\Drivers;

use App\Models\Payment;
use App\Modules\Payment\Contracts\PaymentGatewayInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class bKashDriver implements PaymentGatewayInterface
{
    public function charge(array $payload): array
    {
        $transactionId = 'BKASH_' . strtoupper(Str::random(12));
        $amount = (float) $payload['amount'];
        $currency = 'BDT';

        $payment = Payment::create([
            'user_id' => $payload['user_id'] ?? auth()->id(),
            'transaction_id' => $transactionId,
            'provider' => 'bkash',
            'amount' => $amount,
            'currency' => $currency,
            'status' => 'pending',
            'payment_method' => 'bkash_wallet',
            'raw_response' => ['gateway' => 'bKash Tokenized Checkout'],
        ]);

        return [
            'redirect_url' => url("/api/v1/payment/bkash/checkout?tx={$transactionId}"),
            'transaction_id' => $transactionId,
            'payment' => $payment,
        ];
    }

    public function verify(string $transactionId): Payment
    {
        return Payment::where('transaction_id', $transactionId)->firstOrFail();
    }

    public function handleWebhook(Request $request): array
    {
        $transactionId = $request->input('paymentID') ?? $request->input('transaction_id');
        $status = $request->input('status');

        if ($transactionId) {
            $payment = Payment::where('transaction_id', $transactionId)->first();
            if ($payment) {
                $payment->update([
                    'status' => $status === 'Completed' ? 'completed' : 'failed',
                    'raw_response' => $request->all(),
                ]);
            }
        }

        return ['status' => 'success'];
    }
}
