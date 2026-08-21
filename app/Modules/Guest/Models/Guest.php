<?php

namespace App\Modules\Guest\Models;

use App\Modules\Reservation\Models\Reservation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guest extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'document_number',
        'notes',
    ];

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
