<?php

declare (strict_types = 1);

namespace Dumplie\Domain\CustomerService\OrderState;

use Dumplie\Domain\CustomerService\Exception\InvalidTransitionException;
use Dumplie\Domain\CustomerService\OrderState;

final class Paid implements OrderState
{
    /**
     * @throws InvalidTransitionException
     */
    public function pay(): OrderState
    {
        throw InvalidTransitionException::unexpectedTransition('paid', 'paid');
    }

    /**
     * @throws InvalidTransitionException
     */
    public function cancel(): OrderState
    {
        throw InvalidTransitionException::unexpectedTransition('paid', 'canceled');
    }

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
        throw InvalidTransitionException::unexpectedTransition('paid', 'prepared');
    }

    /**
     * @throws InvalidTransitionException
     */
    public function refund(): OrderState
    {
        throw InvalidTransitionException::unexpectedTransition('paid', 'refunded');
    }

    /**
     * @throws InvalidTransitionException
     */
    public function send(): OrderState
    {
        throw InvalidTransitionException::unexpectedTransition('paid', 'sent');
    }
}
