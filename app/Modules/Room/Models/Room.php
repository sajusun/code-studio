<?php

namespace App\Modules\Room\Models;

use App\Modules\Reservation\Models\Reservation;
use App\Modules\Shared\Enums\RoomStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = [
        'room_type_id',
        'number',
        'floor',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'status' => RoomStatus::class,
            'floor' => 'integer',
        ];
    }

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
