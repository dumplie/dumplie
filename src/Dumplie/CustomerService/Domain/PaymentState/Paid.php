<?php

declare (strict_types = 1);

namespace Dumplie\CustomerService\Domain\PaymentState;

use Dumplie\CustomerService\Domain\Exception\InvalidTransitionException;
use Dumplie\CustomerService\Domain\PaymentState;

final class Paid implements PaymentState
{
    /**
     * @throws InvalidTransitionException
     */
    public function pay(): PaymentState
    {
        throw InvalidTransitionException::unexpectedTransition('paid', 'paid');
    }

    /**
     * @throws InvalidTransitionException
     */
    public function reject(): PaymentState
    {
        throw InvalidTransitionException::unexpectedTransition('paid', 'rejected');
    }
}
