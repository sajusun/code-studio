<?php

namespace App\Modules\Guest\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGuestRequest extends FormRequest
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
            'email' => ['required', 'email', 'max:255', 'unique:guests,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'document_number' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
