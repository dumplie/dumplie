<?php

declare (strict_types = 1);

namespace Dumplie\CustomerService\Domain\OrderState;

use Dumplie\CustomerService\Domain\Exception\InvalidTransitionException;
use Dumplie\CustomerService\Domain\OrderState;

final class Created implements OrderState
{
    /**
     * @return OrderState
     */
    public function accept(): OrderState
    {
        return new Accepted();
    }

    /**
     * @return OrderState
     */
    public function reject(): OrderState
    {
        return new Rejected();
    }

    /**
     * @throws InvalidTransitionException
     */
    public function prepare(): OrderState
    {
        throw InvalidTransitionException::unexpectedTransition('created', 'prepared');
    }

    /**
     * @throws InvalidTransitionException
     */
    public function refund(): OrderState
    {
        throw InvalidTransitionException::unexpectedTransition('created', 'refunded');
    }

    /**
     * @throws InvalidTransitionException
     */
    public function send(): OrderState
    {
        throw InvalidTransitionException::unexpectedTransition('created', 'sent');
    }
}
