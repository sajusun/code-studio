<?php

namespace App\Modules\Billing\Models;

use App\Modules\Guest\Models\Guest;
use App\Modules\Shared\Enums\InvoiceStatus;
use App\Modules\Stay\Models\Stay;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'stay_id',
        'guest_id',
        'status',
        'nights',
        'room_charges',
        'service_charges',
        'tax_amount',
        'total_amount',
        'amount_paid',
        'issued_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => InvoiceStatus::class,
            'nights' => 'integer',
            'room_charges' => 'decimal:2',
            'service_charges' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'amount_paid' => 'decimal:2',
            'issued_at' => 'datetime',
        ];
    }

    public function stay(): BelongsTo
    {
        return $this->belongsTo(Stay::class);
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function balanceDue(): float
    {
        return (float) $this->total_amount - (float) $this->amount_paid;
    }
}
