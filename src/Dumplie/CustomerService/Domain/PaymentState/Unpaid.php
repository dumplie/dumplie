<?php

declare (strict_types = 1);

namespace Dumplie\CustomerService\Domain\PaymentState;

use Dumplie\CustomerService\Domain\OrderState;
use Dumplie\CustomerService\Domain\PaymentState;

final class Unpaid implements PaymentState
{
    /**
     * @return PaymentState
     */
    public function pay(): PaymentState
    {
        return new Paid();
    }

    /**
     * @return PaymentState
     */
    public function reject(): PaymentState
    {
        return new Rejected();
    }
}
