<?php

declare (strict_types = 1);

namespace Dumplie\CustomerService\Domain\Exception;

class InvalidTransitionException extends Exception
{
    /**
     * @param string $fromState
     * @param string $toState
     *
     * @return InvalidTransitionException
     */
    public static function unexpectedTransition($fromState, $toState): InvalidTransitionException
    {
        return new static(sprintf('Can not transit from "%s" to "%s"', $fromState, $toState));
    }

    /**
     * @param string $state
     *
     * @return InvalidTransitionException
     */
    public static function finalState(string $state): InvalidTransitionException
    {
        return new static(sprintf('Can not transit from final state "%s"', $state));
    }
}
