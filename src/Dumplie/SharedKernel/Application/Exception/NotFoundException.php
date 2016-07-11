<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Application\Exception;

class NotFoundException extends Exception
{
    /**
     * @param string $id
     * @return NotFoundException
     */
    public static function serviceNotFound(string $id) : NotFoundException
    {
        return new self(sprintf("Service with id \"%s\" does not exists.", $id));       
    }
}