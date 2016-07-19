<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Application\Exception\ServiceContainer;

class ServiceNotFoundException extends Exception
{
    /**
     * @param string $id
     * @return ServiceNotFoundException
     */
    public static function withId(string $id) : ServiceNotFoundException
    {
        return new static(sprintf("Service with id \"%s\" can't be found by service locator.", $id));
    }
}