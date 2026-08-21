<?php

namespace App\Modules\Reservation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchAvailabilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'check_in_date' => ['required', 'date', 'after_or_equal:today'],
            'check_out_date' => ['required', 'date', 'after:check_in_date'],
            'room_type_id' => ['nullable', 'integer', 'exists:room_types,id'],
            'guests_count' => ['nullable', 'integer', 'min:1', 'max:10'],
        ];
    }
}
