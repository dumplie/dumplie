<?php

declare (strict_types = 1);

namespace Dumplie\Domain\CustomerService;

use Dumplie\Domain\CustomerService\Exception\InvalidTransitionException;

interface OrderState
{
    /**
     * @throws InvalidTransitionException
     */
    public function pay(): OrderState;

    /**
     * @throws InvalidTransitionException
     */
    public function cancel(): OrderState;

    /**
     * @throws InvalidTransitionException
     */
    public function accept(): OrderState;

    /**
     * @throws InvalidTransitionException
     */
    public function reject(): OrderState;

    /**
     * @throws InvalidTransitionException
     */
    public function prepare(): OrderState;

    /**
     * @throws InvalidTransitionException
     */
    public function refund(): OrderState;

    /**
     * @throws InvalidTransitionException
     */
    public function send(): OrderState;
}
