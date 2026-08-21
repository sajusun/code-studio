<?php

namespace App\Modules\Billing\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount' => (float) $this->amount,
            'method' => $this->method,
            'status' => $this->status->value,
            'transaction_reference' => $this->transaction_reference,
            'paid_at' => $this->paid_at?->toIso8601String(),
            'gateway' => $this->gateway,
            'session_id' => $this->session_id,
        ];
    }
}
