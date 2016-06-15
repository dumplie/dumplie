<?php

declare (strict_types = 1);

namespace Dumplie\Domain\CustomerService\OrderState;

use Dumplie\Domain\CustomerService\Exception\InvalidTransitionException;
use Dumplie\Domain\CustomerService\OrderState;

final class Accepted implements OrderState
{
    /**
     * @throws InvalidTransitionException
     */
    public function accept(): OrderState
    {
        throw InvalidTransitionException::unexpectedTransition('accepted', 'accepted');
    }

    /**
     * @throws InvalidTransitionException
     */
    public function reject(): OrderState
    {
        throw InvalidTransitionException::unexpectedTransition('accepted', 'rejected');
    }

    /**
     * @return OrderState
     */
    public function prepare(): OrderState
    {
        return new Prepared();
    }

    /**
     * @return OrderState
     */
    public function refund(): OrderState
    {
        return new Refunded();
    }

    /**
     * @throws InvalidTransitionException
     */
    public function send(): OrderState
    {
        throw InvalidTransitionException::unexpectedTransition('accepted', 'sent');
    }
}
