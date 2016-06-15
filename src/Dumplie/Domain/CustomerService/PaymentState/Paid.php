<?php

declare (strict_types = 1);

namespace Dumplie\Domain\CustomerService\PaymentState;

use Dumplie\Domain\CustomerService\Exception\InvalidTransitionException;
use Dumplie\Domain\CustomerService\PaymentState;

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
