<?php

namespace App\Modules\Payment\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Modules\Payment\Services\PaymentManager;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected PaymentManager $paymentManager
    ) {}

    public function checkout(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'currency' => 'nullable|string|size:3',
            'provider' => 'nullable|string|in:stripe,sslcommerz,bkash,chapa',
            'description' => 'nullable|string',
            'return_url' => 'nullable|url',
        ]);

        $provider = $validated['provider'] ?? config('payment.default', 'stripe');
        $driver = $this->paymentManager->driver($provider);

        $result = $driver->charge([
            'amount' => $validated['amount'],
            'currency' => $validated['currency'] ?? config('payment.currency', 'USD'),
            'user_id' => auth()->id(),
            'description' => $validated['description'] ?? 'Payment Checkout',
            'return_url' => $validated['return_url'] ?? null,
        ]);

        return self::success('Payment checkout initiated successfully', $result);
    }

    public function verify(string $transactionId): JsonResponse
    {
        $payment = Payment::where('transaction_id', $transactionId)->firstOrFail();
        return self::success('Payment details retrieved', $payment);
    }

    public function webhook(Request $request, string $provider): JsonResponse
    {
        $driver = $this->paymentManager->driver($provider);
        $result = $driver->handleWebhook($request);

        return response()->json($result);
    }
}
