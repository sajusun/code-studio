<?php

namespace App\Modules\Reservation\Models;

use App\Modules\Guest\Models\Guest;
use App\Modules\Room\Models\Room;
use App\Modules\Shared\Enums\ReservationStatus;
use App\Modules\Stay\Models\Stay;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Reservation extends Model
{
    protected $fillable = [
        'reference',
        'room_id',
        'guest_id',
        'check_in_date',
        'check_out_date',
        'guests_count',
        'status',
        'nightly_rate',
        'estimated_total',
        'special_requests',
        'payment_status',
        'payment_method',
        'confirmed_at',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'check_in_date' => 'date',
            'check_out_date' => 'date',
            'status' => ReservationStatus::class,
            'nightly_rate' => 'decimal:2',
            'estimated_total' => 'decimal:2',
            'guests_count' => 'integer',
            'confirmed_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    public function stay(): HasOne
    {
        return $this->hasOne(Stay::class);
    }
}
