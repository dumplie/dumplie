<?php

declare (strict_types = 1);

namespace Dumplie\Application\Exception\Metadata;

class HydrationException extends Exception
{
    public static function missingId() : HydrationException
    {
        return new self("Data without id can't be hydratred");
    }
}