<?php

declare (strict_types = 1);

namespace Dumplie\Metadata\Application\Exception;

use Coduo\ToString\StringConverter;
use Dumplie\Metadata\Application\MetadataId;

class NotFoundException extends Exception
{
    /**
     * @param string $name
     * @return NotFoundException
     */
    public static function model(string $name) : NotFoundException
    {
        return new self(sprintf("Model with name \"%s\" does not exists", $name));
    }

    /**
     * @param MetadataId $id
     * @return NotFoundException
     */
    public static function metadata(MetadataId $id) : NotFoundException
    {
        return new self(sprintf("Metadata with id \"%s\" does not exists", (string) $id));
    }

    /**
     * @param array $criteria
     * @return NotFoundException
     */
    public static function metadataWithCriteria(array $criteria = []) : NotFoundException
    {
        return new self(sprintf("Metadata with criteria \"%s\" does not exists", (string) new StringConverter($criteria)));
    }
}
