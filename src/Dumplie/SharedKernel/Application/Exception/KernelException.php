<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Application\Exception;

class KernelException extends Exception
{
    /**
     * @param string $extensionClass
     * @return KernelException
     */
    public static function missingExtension(string $extensionClass) : KernelException
    {
        return new self(sprintf("Extension \"%s\" is missing, can't build service container.", $extensionClass));
    }

    public static function notBuilt() : KernelException
    {
        return new self("Kernel wasn't built yet, can't boot.");
    }
}