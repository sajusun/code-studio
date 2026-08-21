<?php

namespace App\Modules\Reservation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'room_id' => ['required', 'integer', 'exists:rooms,id'],
            'guest_id' => ['required', 'integer', 'exists:guests,id'],
            'check_in_date' => ['required', 'date', 'after_or_equal:today'],
            'check_out_date' => ['required', 'date', 'after:check_in_date'],
            'guests_count' => ['nullable', 'integer', 'min:1', 'max:10'],
        'payment_method' => ['nullable', 'string', 'in:stripe,paypal,on_arrival'],
        ];
    }
}
