<?php

namespace App\Modules\Billing\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecordPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:0.01'],
            'method' => ['required', 'string', 'in:cash,card,bank_transfer,other,stripe,paypal'],
            'transaction_reference' => ['nullable', 'string', 'max:100'],
        ];
    }
}
