<?php

namespace App\Modules\Billing\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Billing\Http\Requests\InitiatePaymentRequest;
use App\Modules\Billing\Services\BillingService;
use App\Modules\Billing\PaymentGatewayManager;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Exception;

class PaymentController extends Controller
{
    public function __construct(
        private readonly BillingService $billingService,
        private readonly PaymentGatewayManager $gatewayManager,
    ) {}

    /**
     * Initiate online payment redirect session.
     */
    public function initiate(InitiatePaymentRequest $request, int $invoice): JsonResponse
    {
        try {
            $sessionData = $this->billingService->initiateOnlinePayment(
                invoiceId: $invoice,
                gatewayName: $request->validated('gateway'),
                amount: $request->validated('amount') ? (float) $request->validated('amount') : null
            );

            return response()->json([
                'success' => true,
                'redirect_url' => $sessionData->redirectUrl,
                'session_id' => $sessionData->sessionId,
                'gateway' => $sessionData->gateway,
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        } catch (Exception $e) {
            Log::error('Failed to initiate online payment', [
                'invoice_id' => $invoice,
                'gateway' => $request->validated('gateway'),
                'exception' => $e,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to initiate payment. Please try again.',
            ], 500);
        }
    }

    /**
     * Stripe Webhook endpoint.
     */
    public function stripeWebhook(Request $request): JsonResponse
    {
        try {
            $payload = $request->getContent();
            $headers = [
                'stripe-signature' => $request->header('stripe-signature'),
            ];

            $gateway = $this->gatewayManager->gateway('stripe');
            $result = $gateway->handleWebhook($payload, $headers);

            if ($result) {
                // Complete the payment record and update invoice status
                $payment = $this->billingService->completeOnlinePayment($result);

                // If payment completed, create Stay and generate Invoice if not already present
                if ($payment && $result->status === 'completed') {
                    // Load reservation via invoice
                    $invoice = \App\Modules\Billing\Models\Invoice::find($result->invoiceId);
                    if ($invoice && $invoice->stay === null) {
                        $reservation = $invoice->stay ? $invoice->stay->reservation : \App\Modules\Reservation\Models\Reservation::find($invoice->stay->reservation_id);
                        if ($reservation) {
                            // Create Stay linked to reservation, room, and guest
                            $stay = \App\Modules\Stay\Models\Stay::create([
                                'reservation_id' => $reservation->id,
                                'room_id' => $reservation->room_id,
                                'guest_id' => $reservation->guest_id,
                                'status' => \App\Modules\Shared\Enums\StayStatus::CheckedIn,
                            ]);

                            // Generate invoice for the stay (includes room charges etc.)
                            $this->billingService->generateInvoiceForStay($stay);
                        }
                    }
                }
            }

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            Log::error('Stripe Webhook Failed', ['exception' => $e->getMessage()]);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }

    /**
     * PayPal Webhook endpoint.
     */
    public function paypalWebhook(Request $request): JsonResponse
    {
        try {
            $payload = $request->getContent();
            $headers = $request->headers->all();

            // Normalize headers for convenience
            $normalizedHeaders = [];
            foreach ($headers as $key => $values) {
                $normalizedHeaders[strtolower($key)] = $values[0] ?? '';
            }

            $gateway = $this->gatewayManager->gateway('paypal');
            $result = $gateway->handleWebhook($payload, $normalizedHeaders);

            if ($result) {
                $this->billingService->completeOnlinePayment($result);
            }

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            Log::error('PayPal Webhook Failed', ['exception' => $e->getMessage()]);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }
}
