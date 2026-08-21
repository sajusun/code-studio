<?php

namespace App\Modules\Room\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'base_rate' => ['required', 'numeric', 'min:0'],
            'capacity' => ['required', 'integer', 'min:1'],
        ];
    }
}
