<?php

namespace App\Modules\Room\Http\Requests;

use App\Modules\Shared\Enums\RoomStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $room = $this->route('room');
        $roomId = is_numeric($room) ? $room : $room?->id;

        return [
            'room_type_id' => ['sometimes', 'required', 'integer', 'exists:room_types,id'],
            'number' => ['sometimes', 'required', 'string', Rule::unique('rooms', 'number')->ignore($roomId)],
            'floor' => ['sometimes', 'integer', 'min:0'],
            'status' => ['sometimes', 'required', Rule::enum(RoomStatus::class)],
            'notes' => ['nullable', 'string'],
        ];
    }
}
