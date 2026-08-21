<?php

namespace App\Modules\Billing\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InitiatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'gateway' => ['required', 'string', 'in:stripe,paypal'],
            'amount'  => ['nullable', 'numeric', 'min:0.01'],
        ];
    }
}
