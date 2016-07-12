<?php

declare (strict_types = 1);

namespace Dumplie\Metadata\Application\Schema;

use Dumplie\Metadata\Application\Exception\InvalidArgumentException;

final class TypeSchema
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var FieldDefinition[];
     */
    private $definitions;

    /**
     * @param string $name
     * @param array $fields
     * @throws InvalidArgumentException
     */
    public function __construct(string $name, array $fields)
    {
        $this->name = $name;
        $this->definitions = [];

        foreach ($fields as $fieldName => $field) {
            $this->addFieldDefinition($fieldName, $field);
        }
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @param string $fieldName
     * @return FieldDefinition
     */
    public function getFieldDefinition(string $fieldName) : FieldDefinition
    {
        return $this->definitions[mb_strtolower($fieldName)];
    }

    /**
     * @param array|string $excluded
     * @return array|FieldDefinition[]
     */
    public function getDefinitions(array $excluded = []) : array
    {
        $definitions = [];
        foreach ($this->definitions as $name => $definition) {
            if (!in_array($name, $excluded, true)) {
                $definitions[$name] = $definition;
            }
        }

        return $definitions;
    }

    /**
     * @param string $fieldName
     * @param FieldDefinition $type
     * @throws InvalidArgumentException
     */
    private function addFieldDefinition(string $fieldName, FieldDefinition $type)
    {
        if (array_key_exists(mb_strtolower($fieldName), $this->definitions)) {
            throw InvalidArgumentException::fieldNameAlreadyExists($fieldName, $this->name);
        }

        $this->definitions[mb_strtolower($fieldName)] = $type;
    }
}
