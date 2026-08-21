<?php

namespace App\Modules\Reservation\Http\Resources;

use App\Modules\Guest\Http\Resources\GuestResource;
use App\Modules\Room\Http\Resources\RoomResource;
use App\Modules\Stay\Http\Resources\StayResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'check_in_date' => $this->check_in_date->toDateString(),
            'check_out_date' => $this->check_out_date->toDateString(),
            'guests_count' => $this->guests_count,
            'status' => $this->status->value,
            'nightly_rate' => (float) $this->nightly_rate,
            'estimated_total' => (float) $this->estimated_total,
            'special_requests' => $this->special_requests,
            'confirmed_at' => $this->confirmed_at?->toIso8601String(),
            'cancelled_at' => $this->cancelled_at?->toIso8601String(),
            'room' => new RoomResource($this->whenLoaded('room')),
            'guest' => new GuestResource($this->whenLoaded('guest')),
            'stay' => new StayResource($this->whenLoaded('stay')),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
