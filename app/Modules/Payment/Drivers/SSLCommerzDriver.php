<?php

namespace App\Modules\Payment\Drivers;

use App\Models\Payment;
use App\Modules\Payment\Contracts\PaymentGatewayInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SSLCommerzDriver implements PaymentGatewayInterface
{
    protected string $storeId;
    protected string $storePassword;
    protected bool $isSandbox;

    public function __construct()
    {
        $this->storeId = config('payment.drivers.sslcommerz.store_id', '');
        $this->storePassword = config('payment.drivers.sslcommerz.store_password', '');
        $this->isSandbox = config('payment.drivers.sslcommerz.is_sandbox', true);
    }

    public function charge(array $payload): array
    {
        $transactionId = 'SSLC_' . strtoupper(Str::random(12));
        $amount = (float) $payload['amount'];
        $currency = strtoupper($payload['currency'] ?? 'BDT');

        $baseUrl = $this->isSandbox
            ? 'https://sandbox.sslcommerz.com'
            : 'https://securepay.sslcommerz.com';

        $postData = [
            'store_id' => $this->storeId,
            'store_passwd' => $this->storePassword,
            'total_amount' => $amount,
            'currency' => $currency,
            'tran_id' => $transactionId,
            'success_url' => url('/api/v1/payment/webhook/sslcommerz?tx=' . $transactionId),
            'fail_url' => url('/api/v1/payment/webhook/sslcommerz?tx=' . $transactionId),
            'cancel_url' => url('/api/v1/payment/webhook/sslcommerz?tx=' . $transactionId),
            'cus_name' => $payload['user_name'] ?? 'Customer',
            'cus_email' => $payload['user_email'] ?? 'customer@example.com',
            'cus_add1' => 'Dhaka',
            'cus_city' => 'Dhaka',
            'cus_country' => 'Bangladesh',
            'cus_phone' => '01700000000',
            'shipping_method' => 'NO',
            'product_name' => $payload['description'] ?? 'Services',
            'product_category' => 'Service',
            'product_profile' => 'general',
        ];

        $response = Http::asForm()->post("{$baseUrl}/gwprocess/v4/api.php", $postData);
        $result = $response->json();

        $redirectUrl = $result['GatewayPageURL'] ?? null;

        $payment = Payment::create([
            'user_id' => $payload['user_id'] ?? auth()->id(),
            'transaction_id' => $transactionId,
            'provider' => 'sslcommerz',
            'amount' => $amount,
            'currency' => $currency,
            'status' => 'pending',
            'payment_method' => 'sslcommerz',
            'raw_response' => $result,
        ]);

        return [
            'redirect_url' => $redirectUrl,
            'transaction_id' => $transactionId,
            'payment' => $payment,
        ];
    }

    public function verify(string $transactionId): Payment
    {
        $payment = Payment::where('transaction_id', $transactionId)->firstOrFail();
        return $payment;
    }

    public function handleWebhook(Request $request): array
    {
        $tranId = $request->input('tran_id') ?? $request->query('tx');
        $status = $request->input('status');

        if ($tranId) {
            $payment = Payment::where('transaction_id', $tranId)->first();
            if ($payment) {
                $newStatus = ($status === 'VALID' || $status === 'VALIDATED') ? 'completed' : 'failed';
                $payment->update([
                    'status' => $newStatus,
                    'raw_response' => $request->all(),
                ]);
            }
        }

        return ['status' => 'success'];
    }
}
