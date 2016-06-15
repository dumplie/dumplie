<?php

declare (strict_types = 1);

namespace Dumplie\Domain\CustomerService\PaymentState;

use Dumplie\Domain\CustomerService\Exception\InvalidTransitionException;
use Dumplie\Domain\CustomerService\PaymentState;

final class Rejected implements PaymentState
{
    /**
     * @throws InvalidTransitionException
     */
    public function pay(): PaymentState
    {
        throw InvalidTransitionException::finalState('rejected');
    }

    /**
     * @throws InvalidTransitionException
     */
    public function reject(): PaymentState
    {
        throw InvalidTransitionException::finalState('rejected');
    }
}
