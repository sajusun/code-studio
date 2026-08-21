<?php

namespace App\Modules\Room\Http\Requests;

use App\Modules\Shared\Enums\RoomStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoomStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::enum(RoomStatus::class)],
        ];
    }
}
