<?php

namespace App\Modules\Billing\Models;

use App\Modules\Shared\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'invoice_id',
        'reservation_id',
        'amount',
        'method',
        'status',
        'transaction_reference',
        'paid_at',
        'gateway',
        'session_id',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'status' => PaymentStatus::class,
            'paid_at' => 'datetime',
        ];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Reservation\Models\Reservation::class);
    }
}
