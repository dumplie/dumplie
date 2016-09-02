<?php

declare (strict_types = 1);

namespace Dumplie\Metadata\Schema;

use Dumplie\Metadata\Schema;

final class Builder
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var TypeSchema
     */
    private $types;

    public function __construct($name = 'dumplie')
    {
        $this->name = $name;
        $this->types = [];
    }

    /**
     * @param string $name
     * @return Builder
     */
    public function changeName(string $name) : Builder
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param TypeSchema $schema
     * @return Builder
     */
    public function addType(TypeSchema $schema) : Builder
    {
        $this->types[$schema->name()] = $schema;

        return $this;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasType(string $name) : bool
    {
        return array_key_exists($name, $this->types);
    }

    /**
     * @param string $name
     * @return TypeSchema
     */
    public function getType(string $name) : TypeSchema
    {
        return $this->types[$name];
    }

    /**
     * @return Schema
     */
    public function build() : Schema
    {
        $schema = new Schema($this->name);

        foreach ($this->types as $type) {
            $schema->add($type);
        }

        return $schema;
    }
}