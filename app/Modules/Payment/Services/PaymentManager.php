<?php

namespace App\Modules\Payment\Services;

use App\Modules\Payment\Contracts\PaymentGatewayInterface;
use App\Modules\Payment\Drivers\bKashDriver;
use App\Modules\Payment\Drivers\SSLCommerzDriver;
use App\Modules\Payment\Drivers\StripeDriver;
use InvalidArgumentException;

class PaymentManager
{
    public function driver(?string $driver = null): PaymentGatewayInterface
    {
        $driver = $driver ?? config('payment.default', 'stripe');

        return match (strtolower($driver)) {
            'stripe' => new StripeDriver(),
            'sslcommerz' => new SSLCommerzDriver(),
            'bkash' => new bKashDriver(),
            default => throw new InvalidArgumentException("Unsupported payment driver: [{$driver}]"),
        };
    }
}
