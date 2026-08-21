<?php

namespace App\Modules\Stay\Models;

use App\Modules\Billing\Models\Invoice;
use App\Modules\Guest\Models\Guest;
use App\Modules\Reservation\Models\Reservation;
use App\Modules\Room\Models\Room;
use App\Modules\Shared\Enums\StayStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Stay extends Model
{
    protected $fillable = [
        'reservation_id',
        'room_id',
        'guest_id',
        'status',
        'checked_in_at',
        'checked_out_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => StayStatus::class,
            'checked_in_at' => 'datetime',
            'checked_out_at' => 'datetime',
        ];
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }
}
