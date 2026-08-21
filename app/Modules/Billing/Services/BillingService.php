<?php

namespace App\Modules\Billing\Services;

use App\Modules\Billing\DTOs\RecordPaymentData;
use App\Modules\Billing\DTOs\ServiceChargeData;
use App\Modules\Billing\Models\Invoice;
use App\Modules\Billing\Models\InvoiceItem;
use App\Modules\Billing\Models\Payment;
use App\Modules\Billing\PaymentGatewayManager;
use App\Modules\Billing\DTOs\PaymentSessionData;
use App\Modules\Billing\DTOs\WebhookResult;
use App\Modules\Billing\Repositories\Contracts\InvoiceRepositoryInterface;
use App\Modules\Reservation\Services\ReservationService;
use App\Modules\Shared\Enums\InvoiceStatus;
use App\Modules\Shared\Enums\PaymentStatus;
use App\Modules\Stay\Models\Stay;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BillingService
{
    private const TAX_RATE = 0.10;

    public function __construct(
        private readonly InvoiceRepositoryInterface $invoices,
        private readonly ReservationService $reservationService,
        private readonly ?PaymentGatewayManager $gatewayManager = null,
    ) {}

    public function calculateNights(CarbonInterface $checkIn, CarbonInterface $checkOut): int
    {
        return $this->reservationService->calculateNights($checkIn, $checkOut);
    }

    public function generateInvoiceForStay(Stay $stay, array $services = []): Invoice
    {
        return DB::transaction(function () use ($stay, $services) {
            $stay->loadMissing(['reservation', 'guest', 'room']);

            $reservation = $stay->reservation;
            $nights = $this->calculateNights(
                $reservation->check_in_date,
                $reservation->check_out_date,
            );
            $nightlyRate = (float) $reservation->nightly_rate;
            $roomCharges = round($nights * $nightlyRate, 2);

            $invoice = $this->invoices->create([
                'invoice_number' => $this->generateInvoiceNumber(),
                'stay_id' => $stay->id,
                'guest_id' => $stay->guest_id,
                'status' => InvoiceStatus::Draft,
                'nights' => $nights,
                'room_charges' => $roomCharges,
                'service_charges' => 0,
                'tax_amount' => 0,
                'total_amount' => 0,
                'amount_paid' => 0,
            ]);

            InvoiceItem::query()->create([
                'invoice_id' => $invoice->id,
                'description' => "Room {$stay->room->number} — {$nights} night(s)",
                'type' => 'room',
                'quantity' => $nights,
                'unit_price' => $nightlyRate,
                'total_price' => $roomCharges,
            ]);

            foreach ($services as $service) {
                $this->addServiceCharge($invoice->id, $service);
            }

            $invoice = $this->recalculateTotals($invoice->id);

            // Link pre-paid reservation payments to this new invoice
            $payments = Payment::query()
                ->where('reservation_id', $reservation->id)
                ->where('status', PaymentStatus::Completed)
                ->whereNull('invoice_id')
                ->get();

            if ($payments->isNotEmpty()) {
                $totalPaid = 0.0;
                foreach ($payments as $payment) {
                    $payment->update(['invoice_id' => $invoice->id]);
                    $totalPaid += (float) $payment->amount;
                }

                $status = $totalPaid >= (float) $invoice->total_amount
                    ? InvoiceStatus::Paid
                    : InvoiceStatus::Issued;

                $invoice->update([
                    'amount_paid' => $totalPaid,
                    'status' => $status,
                    'issued_at' => now(),
                ]);
            }

            return $invoice->fresh(['items', 'payments', 'stay', 'guest']);
        });
    }

    public function addServiceCharge(int $invoiceId, ServiceChargeData $service): Invoice
    {
        return DB::transaction(function () use ($invoiceId, $service) {
            $invoice = $this->invoices->findByIdOrFail($invoiceId);

            if ($invoice->status === InvoiceStatus::Paid) {
                throw new \InvalidArgumentException('Cannot add charges to a paid invoice.');
            }

            InvoiceItem::query()->create([
                'invoice_id' => $invoice->id,
                'description' => $service->description,
                'type' => $service->type,
                'quantity' => $service->quantity,
                'unit_price' => $service->unitPrice,
                'total_price' => $service->total(),
            ]);

            return $this->recalculateTotals($invoiceId);
        });
    }

    public function issueInvoice(int $invoiceId): Invoice
    {
        $invoice = $this->invoices->findByIdOrFail($invoiceId);
        $invoice->update([
            'status' => InvoiceStatus::Issued,
            'issued_at' => now(),
        ]);

        return $invoice->fresh(['items', 'payments', 'stay', 'guest']);
    }

    public function recordPayment(RecordPaymentData $data): Payment
    {
        return DB::transaction(function () use ($data) {
            $invoice = $this->invoices->findByIdOrFail($data->invoiceId);
            $balanceDue = $invoice->balanceDue();

            if ($data->amount <= 0) {
                throw new \InvalidArgumentException('Payment amount must be greater than zero.');
            }

            if ($data->amount > $balanceDue) {
                throw new \InvalidArgumentException(
                    "Payment amount ({$data->amount}) exceeds balance due ({$balanceDue})."
                );
            }

            $payment = Payment::query()->create([
                'invoice_id' => $invoice->id,
                'amount' => $data->amount,
                'method' => $data->method,
                'status' => PaymentStatus::Completed,
                'transaction_reference' => $data->transactionReference,
                'paid_at' => now(),
            ]);

            $newAmountPaid = (float) $invoice->amount_paid + $data->amount;
            $status = $newAmountPaid >= (float) $invoice->total_amount
                ? InvoiceStatus::Paid
                : $invoice->status;

            $invoice->update([
                'amount_paid' => $newAmountPaid,
                'status' => $status,
            ]);

            return $payment;
        });
    }

    public function recalculateTotals(int $invoiceId): Invoice
    {
        $invoice = $this->invoices->findByIdOrFail($invoiceId);
        $items = $invoice->items;

        $roomCharges = (float) $items->where('type', 'room')->sum('total_price');
        $serviceCharges = (float) $items->where('type', '!=', 'room')->sum('total_price');
        $subtotal = $roomCharges + $serviceCharges;
        $taxAmount = round($subtotal * self::TAX_RATE, 2);
        $totalAmount = round($subtotal + $taxAmount, 2);

        $invoice->update([
            'room_charges' => $roomCharges,
            'service_charges' => $serviceCharges,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
        ]);

        return $invoice->fresh(['items', 'payments', 'stay', 'guest']);
    }

    public function initiateOnlinePayment(int $invoiceId, string $gatewayName, ?float $amount = null): PaymentSessionData
    {
        return DB::transaction(function () use ($invoiceId, $gatewayName, $amount) {
            $invoice = $this->invoices->findByIdOrFail($invoiceId);

            if ($invoice->status === InvoiceStatus::Paid) {
                throw new \InvalidArgumentException('This invoice is already paid.');
            }

            $balanceDue = $invoice->balanceDue();
            $paymentAmount = $amount ?? $balanceDue;

            if ($paymentAmount <= 0) {
                throw new \InvalidArgumentException('Payment amount must be greater than zero.');
            }

            if ($paymentAmount > $balanceDue) {
                throw new \InvalidArgumentException(
                    "Payment amount ({$paymentAmount}) exceeds balance due ({$balanceDue})."
                );
            }

            $manager = $this->gatewayManager ?? app(PaymentGatewayManager::class);
            $gateway = $manager->gateway($gatewayName);
            $sessionData = $gateway->createSession($invoice, $paymentAmount, 'USD');

            // Create a pending online payment record
            Payment::query()->create([
                'invoice_id' => $invoice->id,
                'amount' => $paymentAmount,
                'method' => $gatewayName,
                'gateway' => $gatewayName,
                'session_id' => $sessionData->sessionId,
                'status' => PaymentStatus::Processing,
            ]);

            return $sessionData;
        });
    }

    public function initiateReservationPayment(\App\Modules\Reservation\Models\Reservation $reservation, string $gatewayName): PaymentSessionData
    {
        return DB::transaction(function () use ($reservation, $gatewayName) {
            if ($reservation->payment_status === 'paid') {
                throw new \InvalidArgumentException('This reservation is already paid.');
            }

            $amount = (float) $reservation->estimated_total;
            if ($amount <= 0) {
                throw new \InvalidArgumentException('Reservation amount must be greater than zero.');
            }

            $manager = $this->gatewayManager ?? app(PaymentGatewayManager::class);
            $gateway = $manager->gateway($gatewayName);
            $sessionData = $gateway->createSession($reservation, $amount, 'USD');

            // Create a pending online payment record
            Payment::query()->create([
                'reservation_id' => $reservation->id,
                'amount' => $amount,
                'method' => $gatewayName,
                'gateway' => $gatewayName,
                'session_id' => $sessionData->sessionId,
                'status' => PaymentStatus::Processing,
            ]);

            return $sessionData;
        });
    }

    public function completeOnlinePayment(WebhookResult $result): ?Payment
    {
        return DB::transaction(function () use ($result) {
            // Find the processing payment
            $paymentQuery = Payment::query()
                ->where('gateway', $result->gateway);

            if ($result->invoiceId) {
                $paymentQuery->where('invoice_id', $result->invoiceId);
            } elseif ($result->reservationId) {
                $paymentQuery->where('reservation_id', $result->reservationId);
            } else {
                return null;
            }

            $payment = $paymentQuery->where(function ($query) use ($result) {
                    $query->where('session_id', $result->transactionReference)
                          ->orWhere('transaction_reference', $result->transactionReference)
                          ->orWhere('status', PaymentStatus::Processing);
                })
                ->latest()
                ->first();

            if (!$payment) {
                Log::warning("No matching processing payment found for webhook", [
                    'gateway' => $result->gateway,
                    'invoice_id' => $result->invoiceId,
                    'reservation_id' => $result->reservationId,
                    'transaction_reference' => $result->transactionReference,
                ]);
                return null;
            }

            if ($payment->status === PaymentStatus::Completed) {
                return $payment; // Already processed
            }

            if ($result->status === 'completed') {
                $payment->update([
                    'status' => PaymentStatus::Completed,
                    'transaction_reference' => $result->transactionReference,
                    'paid_at' => now(),
                ]);

                if ($payment->invoice_id) {
                    $invoice = $this->invoices->findByIdOrFail($payment->invoice_id);
                    $newAmountPaid = (float) $invoice->amount_paid + $payment->amount;
                    $status = $newAmountPaid >= (float) $invoice->total_amount
                        ? InvoiceStatus::Paid
                        : $invoice->status;

                    $invoice->update([
                        'amount_paid' => $newAmountPaid,
                        'status' => $status,
                    ]);
                } elseif ($payment->reservation_id) {
                    $reservation = \App\Modules\Reservation\Models\Reservation::find($payment->reservation_id);
                    if ($reservation) {
                        $reservation->update([
                            'status' => \App\Modules\Shared\Enums\ReservationStatus::Confirmed,
                            'payment_status' => 'paid',
                            'confirmed_at' => now(),
                        ]);
                    }
                }
            } else {
                $payment->update([
                    'status' => PaymentStatus::Failed,
                    'transaction_reference' => $result->transactionReference,
                ]);
                if ($payment->reservation_id) {
                    $reservation = \App\Modules\Reservation\Models\Reservation::find($payment->reservation_id);
                    if ($reservation) {
                        $reservation->update([
                            'payment_status' => 'failed',
                        ]);
                    }
                }
            }

            return $payment;
        });
    }

    private function generateInvoiceNumber(): string
    {
        return 'INV-'.now()->format('Ymd').'-'.strtoupper(Str::random(6));
    }
}
