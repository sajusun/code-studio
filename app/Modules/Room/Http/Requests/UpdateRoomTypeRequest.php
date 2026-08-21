<?php

namespace App\Modules\Room\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoomTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'base_rate' => ['sometimes', 'required', 'numeric', 'min:0'],
            'capacity' => ['sometimes', 'required', 'integer', 'min:1'],
        ];
    }
}
