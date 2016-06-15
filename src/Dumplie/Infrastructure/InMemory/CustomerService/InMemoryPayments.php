<?php

declare (strict_types = 1);

namespace Dumplie\Infrastructure\InMemory\CustomerService;

use Dumplie\Domain\CustomerService\Exception\PaymentNotFoundException;
use Dumplie\Domain\CustomerService\Payment;
use Dumplie\Domain\CustomerService\PaymentId;
use Dumplie\Domain\CustomerService\Payments;

final class InMemoryPayments implements Payments
{
    /**
     * @var array
     */
    private $payments;

    /**
     * @param array|Payment[] $orders
     */
    public function __construct(array $orders = [])
    {
        $this->payments = [];

        foreach ($orders as $cart) {
            $this->add($cart);
        }
    }

    /**
     * @param PaymentId $paymentId
     *
     * @return Payment
     * @throws PaymentNotFoundException
     */
    public function getById(PaymentId $paymentId) : Payment
    {
        if (!array_key_exists((string)$paymentId, $this->payments)) {
            throw PaymentNotFoundException::byId($paymentId);
        }

        return $this->payments[(string)$paymentId];
    }

    /**
     * @param Payment $payment
     */
    public function add(Payment $payment)
    {
        $this->payments[(string)$payment->id()] = $payment;
    }
}
