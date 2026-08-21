<?php

namespace App\Modules\Billing;

use App\Modules\Billing\Contracts\PaymentGatewayInterface;
use App\Modules\Billing\Gateways\StripeGateway;
use App\Modules\Billing\Gateways\PaypalGateway;
use InvalidArgumentException;

class PaymentGatewayManager
{
    protected array $gateways = [];

    public function __construct()
    {
        $this->register(new StripeGateway());
        $this->register(new PaypalGateway());
    }

    /**
     * Register a new payment gateway implementation.
     */
    public function register(PaymentGatewayInterface $gateway): void
    {
        $this->gateways[$gateway->getName()] = $gateway;
    }

    /**
     * Resolve a payment gateway by its name.
     */
    public function gateway(string $name): PaymentGatewayInterface
    {
        if (!isset($this->gateways[$name])) {
            throw new InvalidArgumentException("Payment gateway [{$name}] is not supported.");
        }

        return $this->gateways[$name];
    }

    /**
     * Get all registered gateways.
     */
    public function getGateways(): array
    {
        return $this->gateways;
    }
}
