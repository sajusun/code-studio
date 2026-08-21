<?php

namespace App\Modules\Billing\Http\Resources;

use App\Modules\Guest\Http\Resources\GuestResource;
use App\Modules\Stay\Http\Resources\StayResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoice_number' => $this->invoice_number,
            'status' => $this->status->value,
            'nights' => $this->nights,
            'room_charges' => (float) $this->room_charges,
            'service_charges' => (float) $this->service_charges,
            'tax_amount' => (float) $this->tax_amount,
            'total_amount' => (float) $this->total_amount,
            'amount_paid' => (float) $this->amount_paid,
            'balance_due' => $this->balanceDue(),
            'issued_at' => $this->issued_at?->toIso8601String(),
            'items' => InvoiceItemResource::collection($this->whenLoaded('items')),
            'payments' => PaymentResource::collection($this->whenLoaded('payments')),
            'guest' => new GuestResource($this->whenLoaded('guest')),
            'stay' => new StayResource($this->whenLoaded('stay')),
        ];
    }
}
