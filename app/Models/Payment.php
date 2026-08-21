<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'transaction_id',
        'provider',
        'amount',
        'currency',
        'status',
        'payment_method',
        'payable_type',
        'payable_id',
        'raw_response',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'raw_response' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payable(): MorphTo
    {
        return $this->morphTo();
    }
}
