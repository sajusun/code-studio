<?php

namespace App\Modules\Guest\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGuestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['sometimes', 'string', 'max:100'],
            'last_name' => ['sometimes', 'string', 'max:100'],
            'email' => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('guests', 'email')->ignore($this->route('guest')),
            ],
            'phone' => ['nullable', 'string', 'max:30'],
            'document_number' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
