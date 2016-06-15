<?php

declare (strict_types = 1);

namespace Dumplie\Domain\CustomerService\PaymentState;

use Dumplie\Domain\CustomerService\OrderState;
use Dumplie\Domain\CustomerService\PaymentState;

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
