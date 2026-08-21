<?php

namespace App\Modules\Room\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'base_rate' => (float) $this->base_rate,
            'capacity' => $this->capacity,
            'rooms_count' => $this->when(isset($this->rooms_count), $this->rooms_count),
        ];
    }
}
