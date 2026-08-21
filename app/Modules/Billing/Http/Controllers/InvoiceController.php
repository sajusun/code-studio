<?php

namespace App\Modules\Billing\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Billing\DTOs\RecordPaymentData;
use App\Modules\Billing\DTOs\ServiceChargeData;
use App\Modules\Billing\Http\Requests\AddServiceChargeRequest;
use App\Modules\Billing\Http\Requests\RecordPaymentRequest;
use App\Modules\Billing\Http\Resources\InvoiceResource;
use App\Modules\Billing\Http\Resources\PaymentResource;
use App\Modules\Billing\Models\Invoice;
use App\Modules\Billing\Repositories\Contracts\InvoiceRepositoryInterface;
use App\Modules\Billing\Services\BillingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class InvoiceController extends Controller
{
    public function __construct(
        private readonly BillingService $billing,
        private readonly InvoiceRepositoryInterface $invoices,
    ) {}

    public function index(): AnonymousResourceCollection
    {
        return InvoiceResource::collection(
            Invoice::query()
                ->with(['stay.reservation', 'guest'])
                ->latest()
                ->paginate(20)
        );
    }

    public function show(int $invoice): InvoiceResource
    {
        return new InvoiceResource($this->invoices->findByIdOrFail($invoice));
    }

    public function addServiceCharge(AddServiceChargeRequest $request, int $invoice): InvoiceResource
    {
        $updated = $this->billing->addServiceCharge(
            $invoice,
            new ServiceChargeData(
                description: $request->validated('description'),
                unitPrice: (float) $request->validated('unit_price'),
                quantity: $request->integer('quantity', 1),
                type: $request->validated('type', 'service'),
            )
        );

        return new InvoiceResource($updated);
    }

    public function issue(int $invoice): InvoiceResource
    {
        return new InvoiceResource($this->billing->issueInvoice($invoice));
    }

    public function recordPayment(RecordPaymentRequest $request, int $invoice): JsonResponse
    {
        $payment = $this->billing->recordPayment(
            new RecordPaymentData(
                invoiceId: $invoice,
                amount: (float) $request->validated('amount'),
                method: $request->validated('method'),
                transactionReference: $request->validated('transaction_reference'),
            )
        );

        return (new PaymentResource($payment))
            ->response()
            ->setStatusCode(201);
    }
}
