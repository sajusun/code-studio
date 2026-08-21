<?php

namespace App\Modules\Stay\Http\Resources;

use App\Modules\Billing\Http\Resources\InvoiceResource;
use App\Modules\Guest\Http\Resources\GuestResource;
use App\Modules\Reservation\Http\Resources\ReservationResource;
use App\Modules\Room\Http\Resources\RoomResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StayResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status->value,
            'checked_in_at' => $this->checked_in_at?->toIso8601String(),
            'checked_out_at' => $this->checked_out_at?->toIso8601String(),
            'reservation' => new ReservationResource($this->whenLoaded('reservation')),
            'room' => new RoomResource($this->whenLoaded('room')),
            'guest' => new GuestResource($this->whenLoaded('guest')),
            'invoice' => new InvoiceResource($this->whenLoaded('invoice')),
        ];
    }
}
