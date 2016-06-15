<?php

declare (strict_types = 1);

namespace Dumplie\Domain\CustomerService;

use Dumplie\Domain\CustomerService\Exception\InvalidTransitionException;

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
