<?php

declare (strict_types = 1);

namespace Dumplie\Metadata\Application;

use Dumplie\Metadata\Application\Exception\InvalidArgumentException;
use Dumplie\Metadata\Application\Schema\TypeSchema;

final class Metadata
{
    /**
     * @var MetadataId
     */
    private $id;

    /**
     * @var string
     */
    private $typeSchemaName;

    /**
     * @var array
     */
    private $fields;

    /**
     * @param MetadataId $id
     * @param string $typeSchemaName
     * @param array $fields
     * @throws InvalidArgumentException
     */
    public function __construct(MetadataId $id, string $typeSchemaName, array $fields = [])
    {
        $this->id = $id;

        if (empty($typeSchemaName)) {
            throw InvalidArgumentException::emptyValue("type schema name");
        }

        $this->typeSchemaName = $typeSchemaName;
        $this->fields = $fields;
    }

    /**
     * @return MetadataId
     */
    public function id() : MetadataId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function typeSchemaName() : string
    {
        return $this->typeSchemaName;
    }

    /**
     * @return array
     */
    public function fields() : array
    {
        return $this->fields;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function __get(string $name)
    {
        if (!array_key_exists($name, $this->fields)) {
            throw InvalidArgumentException::metadataFieldNotFound($this->typeSchemaName, $name, $this->id);
        }

        return $this->fields[$name];
    }

    /**
     * @param string $name
     * @param $value
     */
    public function __set(string $name, $value)
    {
        $this->fields[$name] = $value;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset(string $name) : bool
    {
        return array_key_exists($name, $this->fields);
    }

    /**
     * @param TypeSchema $definition
     * @return bool
     * @throws InvalidArgumentException
     */
    public function isValid(TypeSchema $definition) : bool
    {
        if (mb_strtolower($definition->name()) !== mb_strtolower($this->typeSchemaName())) {
            throw InvalidArgumentException::unexpectedModel($definition->name(), $this->typeSchemaName());
        }

        foreach ($this->fields as $fieldName => $value) {
            $field = $definition->getFieldDefinition($fieldName);

            if (!$field->isValid($value)) {
                return false;
            }
        }

        return true;
    }
}
