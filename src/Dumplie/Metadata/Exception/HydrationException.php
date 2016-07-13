<?php

declare (strict_types = 1);

namespace Dumplie\Metadata\Exception;

class HydrationException extends Exception
{
    public static function missingId() : HydrationException
    {
        return new self("Data without id can't be hydratred");
    }
}
