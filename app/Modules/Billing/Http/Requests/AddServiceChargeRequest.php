<?php

namespace App\Modules\Billing\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddServiceChargeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'description' => ['required', 'string', 'max:255'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'quantity' => ['nullable', 'integer', 'min:1'],
            'type' => ['nullable', 'string', 'max:50'],
        ];
    }
}
