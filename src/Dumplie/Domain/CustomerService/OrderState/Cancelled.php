<?php

declare (strict_types = 1);

namespace Dumplie\Domain\CustomerService\OrderState;

use Dumplie\Domain\CustomerService\Exception\InvalidTransitionException;
use Dumplie\Domain\CustomerService\OrderState;

final class Cancelled implements OrderState
{
    /**
     * @throws InvalidTransitionException
     */
    public function pay(): OrderState
    {
        throw InvalidTransitionException::finalState('canceled');
    }

    /**
     * @throws InvalidTransitionException
     */
    public function cancel(): OrderState
    {
        throw InvalidTransitionException::finalState('canceled');
    }

    /**
     * @throws InvalidTransitionException
     */
    public function accept(): OrderState
    {
        throw InvalidTransitionException::finalState('canceled');
    }

    /**
     * @throws InvalidTransitionException
     */
    public function reject(): OrderState
    {
        throw InvalidTransitionException::finalState('canceled');
    }

    /**
     * @throws InvalidTransitionException
     */
    public function prepare(): OrderState
    {
        throw InvalidTransitionException::finalState('canceled');
    }

    /**
     * @throws InvalidTransitionException
     */
    public function refund(): OrderState
    {
        throw InvalidTransitionException::finalState('canceled');
    }

    /**
     * @throws InvalidTransitionException
     */
    public function send(): OrderState
    {
        throw InvalidTransitionException::finalState('canceled');
    }
}
