<?php

namespace App\Modules\Room\Http\Requests;

use App\Modules\Shared\Enums\RoomStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'room_type_id' => ['required', 'integer', 'exists:room_types,id'],
            'number' => ['required', 'string', 'unique:rooms,number'],
            'floor' => ['integer', 'min:0'],
            'status' => ['required', Rule::enum(RoomStatus::class)],
            'notes' => ['nullable', 'string'],
        ];
    }
}
