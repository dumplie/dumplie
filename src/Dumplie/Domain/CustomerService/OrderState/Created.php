<?php

declare (strict_types = 1);

namespace Dumplie\Domain\CustomerService\OrderState;

use Dumplie\Domain\CustomerService\Exception\InvalidTransitionException;
use Dumplie\Domain\CustomerService\OrderState;

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
