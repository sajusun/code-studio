<?php

namespace App\Modules\Payment\Http\Controllers;

use App\Modules\Reservation\Models\Reservation;
use App\Modules\Billing\Services\BillingService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CheckoutController
{
    /**
     * Create a Checkout Session for a reservation.
     *
     * Expects JSON payload:
     *   { "gateway": "stripe"|"paypal" }
     */
    public function createSession(Request $request, int $reservationId): JsonResponse
    {
        $data = $request->validate([
            'gateway' => ['required', 'in:stripe,paypal']
        ]);

        $reservation = Reservation::findOrFail($reservationId);

        // Ensure reservation is pending payment
        if ($reservation->payment_status !== 'pending' && $reservation->payment_status !== 'on_arrival') {
            return response()->json([
                'success' => false,
                'message' => 'Reservation is not eligible for payment.'
            ], 400);
        }

        try {
            $billingService = app(BillingService::class);
            $sessionData = $billingService->initiateReservationPayment($reservation, $data['gateway']);

            // Update reservation with chosen method
            $reservation->update([
                'payment_method' => $data['gateway'],
                'payment_status' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'checkout_url' => $sessionData->redirectUrl,
                'session_id' => $sessionData->sessionId,
                'gateway' => $sessionData->gateway,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
