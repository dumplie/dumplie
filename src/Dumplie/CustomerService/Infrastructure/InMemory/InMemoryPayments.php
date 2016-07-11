<?php

declare (strict_types = 1);

namespace Dumplie\CustomerService\Infrastructure\InMemory;

use Dumplie\CustomerService\Domain\Exception\PaymentNotFoundException;
use Dumplie\CustomerService\Domain\Payment;
use Dumplie\CustomerService\Domain\PaymentId;
use Dumplie\CustomerService\Domain\Payments;

final class InMemoryPayments implements Payments
{
    /**
     * @var array
     */
    private $payments;

    /**
     * @param array|Payment[] $payments
     */
    public function __construct(array $payments = [])
    {
        $this->payments = [];

        foreach ($payments as $payment) {
            $this->add($payment);
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
