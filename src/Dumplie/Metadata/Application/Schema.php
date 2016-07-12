<?php

declare (strict_types = 1);

namespace Dumplie\Metadata\Application;

use Dumplie\Metadata\Application\Exception\NotFoundException;
use Dumplie\Metadata\Application\Schema\TypeSchema;

final class Schema
{
    private $name;

    private $types;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->types = [];
    }

    /**
     * @return string
     */
    public function name():string
    {
        return $this->name;
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
     *
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
     * @return TypeSchema[]
     */
    public function types() : array
    {
        return $this->types;
    }
}
