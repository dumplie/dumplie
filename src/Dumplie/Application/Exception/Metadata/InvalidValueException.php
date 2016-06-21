<?php

declare (strict_types = 1);

namespace Dumplie\Application\Exception\Metadata;

use Coduo\ToString\StringConverter;
use Dumplie\Application\Metadata\Schema\FieldDefinition;

final class InvalidValueException extends InvalidArgumentException
{
    public static function valueDoesNotMatchType(FieldDefinition $type, $value) : InvalidValueException
    {
        return new self(sprintf(
            "Value \"%s\" does not match type \"%s\"",
            (string) new StringConverter($value),
            $type->name()
        ));
    }
}