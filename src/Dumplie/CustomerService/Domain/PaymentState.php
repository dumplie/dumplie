<?php

declare (strict_types = 1);

namespace Dumplie\CustomerService\Domain;

use Dumplie\CustomerService\Domain\Exception\InvalidTransitionException;

interface PaymentState
{
    /**
     * @throws InvalidTransitionException
     */
    public function pay(): PaymentState;

    /**
     * @throws InvalidTransitionException
     */
    public function reject(): PaymentState;
}
