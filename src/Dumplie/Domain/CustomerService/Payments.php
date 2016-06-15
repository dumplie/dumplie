<?php

declare (strict_types = 1);

namespace Dumplie\Domain\CustomerService;

use Dumplie\Domain\CustomerService\Exception\PaymentNotFoundException;

interface Payments
{
    /**
     * @param PaymentId $paymentId
     *
     * @throws PaymentNotFoundException
     * @return Payment
     */
    public function getById(PaymentId $paymentId) : Payment;

    /**
     * @param Payment $payment
     */
    public function add(Payment $payment);
}
