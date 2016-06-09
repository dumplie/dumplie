<?php

declare (strict_types = 1);

namespace Dumplie\Domain\CustomerService\OrderState;

use Dumplie\Domain\CustomerService\Exception\InvalidTransitionException;
use Dumplie\Domain\CustomerService\OrderState;

final class Unpaid implements OrderState
{
    /**
     * @return OrderState
     */
    public function pay(): OrderState
    {
        return new Paid();
    }

    /**
     * @return OrderState
     */
    public function cancel(): OrderState
    {
        return new Cancelled();
    }

    /**
     * @throws InvalidTransitionException
     */
    public function accept(): OrderState
    {
        throw InvalidTransitionException::unexpectedTransition('unpaid', 'accepted');
    }

    /**
     * @throws InvalidTransitionException
     */
    public function reject(): OrderState
    {
        throw InvalidTransitionException::unexpectedTransition('unpaid', 'rejected');
    }

    /**
     * @throws InvalidTransitionException
     */
    public function prepare(): OrderState
    {
        throw InvalidTransitionException::unexpectedTransition('unpaid', 'prepared');
    }

    /**
     * @throws InvalidTransitionException
     */
    public function refund(): OrderState
    {
        throw InvalidTransitionException::unexpectedTransition('unpaid', 'refunded');
    }

    /**
     * @throws InvalidTransitionException
     */
    public function send(): OrderState
    {
        throw InvalidTransitionException::unexpectedTransition('unpaid', 'sent');
    }
}
