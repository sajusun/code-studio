<?php

namespace App\Modules\Reservation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePublicReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:190'],
            'phone' => ['required', 'string', 'max:50'],
            'room_id' => ['required', 'integer', 'exists:rooms,id'],
            'check_in_date' => ['required', 'date', 'after_or_equal:today'],
            'check_out_date' => ['required', 'date', 'after:check_in_date'],
            'guests_count' => ['nullable', 'integer', 'min:1', 'max:10'],
            'special_requests' => ['nullable', 'string', 'max:1000'],
            'payment_method' => ['nullable', 'string'],
        ];
    }
}
