<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Domain\Exception;

class UnknownEventException extends Exception
{
    /**
     * @param string $expectedName
     * @param string $receivedName
     * @return UnknownEventException
     * @throws UnknownEventException
     */
    public static function unexpected(string $expectedName, string $receivedName) : UnknownEventException
    {
        throw new self(sprintf("Expected event \"%s\" but got \"%s\"", $expectedName, $receivedName));
    }

    /**
     * @param string $name
     * @return UnknownEventException
     * @throws UnknownEventException
     */
    public static function unsupported(string $name) : UnknownEventException
    {
        throw new self(sprintf("Event with name \"%s\" is not supported by Event Log", $name));
    }
}