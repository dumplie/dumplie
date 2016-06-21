<?php

declare (strict_types = 1);

namespace Dumplie\Application\Metadata;

use Dumplie\Application\Exception\Metadata\NotFoundException;
use Dumplie\Application\Metadata\Schema\TypeSchema;

final class Schema
{
    private $types;

    public function __construct()
    {
        $this->types = [];
    }

    /**
     * @param TypeSchema $typeSchema
     */
    public function add(TypeSchema $typeSchema)
    {
        $this->types[$typeSchema->name()] = $typeSchema;
    }

    /**
     * @param string $typeName
     * @return TypeSchema
     * @throws NotFoundException
     */
    public function get(string $typeName) : TypeSchema
    {
        if (!array_key_exists($typeName, $this->types)) {
            throw NotFoundException::model($typeName);
        }

        return $this->types[$typeName];
    }

    /**
     * @return array
     */
    public function types() : array
    {
        return $this->types;
    }
}