<?php

declare (strict_types = 1);

namespace Dumplie\Metadata\Application\Exception;

final class InvalidTypeException extends InvalidArgumentException
{
    /**
     * @param string $type
     * @param array  $allowed
     *
     * @return InvalidTypeException
     */
    public static function invalidType(string $type, array $allowed) : InvalidTypeException
    {
        return new static(sprintf("Invalid type \"%s\", must be one of: %s", $type, implode(", ", $allowed)));
    }
}
