<?php

declare (strict_types = 1);

namespace Dumplie\Domain\CustomerService\OrderState;

use Dumplie\Domain\CustomerService\Exception\InvalidTransitionException;
use Dumplie\Domain\CustomerService\OrderState;

final class Prepared implements OrderState
{
    /**
     * @throws InvalidTransitionException
     */
    public function pay(): OrderState
    {
        throw InvalidTransitionException::unexpectedTransition('prepared', 'paid');
    }

    /**
     * @throws InvalidTransitionException
     */
    public function cancel(): OrderState
    {
        throw InvalidTransitionException::unexpectedTransition('prepared', 'canceled');
    }

    /**
     * @throws InvalidTransitionException
     */
    public function accept(): OrderState
    {
        throw InvalidTransitionException::unexpectedTransition('prepared', 'accepted');
    }

    /**
     * @throws InvalidTransitionException
     */
    public function reject(): OrderState
    {
        throw InvalidTransitionException::unexpectedTransition('prepared', 'rejected');
    }

    /**
     * @throws InvalidTransitionException
     */
    public function prepare(): OrderState
    {
        throw InvalidTransitionException::unexpectedTransition('prepared', 'prepare');
    }

    /**
     * @throws InvalidTransitionException
     */
    public function refund(): OrderState
    {
        throw InvalidTransitionException::unexpectedTransition('prepared', 'refund');
    }

    /**
     * @return OrderState
     */
    public function send(): OrderState
    {
        return new Sent();
    }
}
